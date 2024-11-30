<?php
if (!isset($_SESSION['usid'])) {
    echo '<script>alert("Please login to checkout.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit;
}

$userID = $_SESSION['usid'];

$orderCheckSql = "SELECT OrderID FROM orders WHERE UserID = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $orderCheckSql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $orderID);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (is_null($orderID)) {
    echo '<script>alert("Please select your area and table to continue with the payment.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
    exit;
}

$sql = "SELECT d.DishanddrinkPrice, c.Quantity, s.ServicesPrice
        FROM cart c
        JOIN dishanddrink d ON c.DishanddrinkID = d.DishanddrinkID
        LEFT JOIN services s ON c.ServicesID = s.ServicesID
        WHERE c.UserID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$totalPrice = 0;
while ($row = mysqli_fetch_array($result)) {
    $itemTotal = ($row['DishanddrinkPrice'] * $row['Quantity']);
    $serviceTotal = $row['ServicesPrice'] ?? 0;
    $totalItemPrice = $itemTotal + $serviceTotal;
    $totalPrice += $totalItemPrice;
}
?>

<h2>Checkout</h2>
<p>Your total amount is: <strong><?php echo $totalPrice; ?> VND</strong></p>

<form action="?page=process_payment.php" method="POST">
    <input type="hidden" name="total_price" value="<?php echo $totalPrice; ?>">
    <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
    <button type="submit" class="btn btn-primary">Confirm Payment</button>
</form>
<hr />
<a href="?page=shoppingcart.php" class="btn btn-secondary">Back to Cart</a>

<?php
?>