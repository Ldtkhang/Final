<?php
if (!isset($_SESSION['usid'])) {
    echo '<script>alert("PLEASE LOGIN");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit;
}

$userID = $_SESSION['usid'];
$sql = "SELECT c.CartID, d.DishanddrinkName, d.DishanddrinkPrice, c.Quantity, s.ServiceName, s.ServicesPrice
        FROM cart c
        JOIN dishanddrink d ON c.DishanddrinkID = d.DishanddrinkID
        LEFT JOIN services s ON c.ServicesID = s.ServicesID
        WHERE c.UserID = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h2>Your Cart</h2>
<table class="table">
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Service</th>
            <th>Service Price</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $totalPrice = 0;
        while ($row = mysqli_fetch_array($result)) {
            $itemTotal = ($row['DishanddrinkPrice'] * $row['Quantity']);
            $serviceTotal = $row['ServicesPrice'] ?? 0; 
            $totalItemPrice = $itemTotal + $serviceTotal;
            $totalPrice += $totalItemPrice; 
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['DishanddrinkName']); ?></td>
                <td><?php echo htmlspecialchars($row['DishanddrinkPrice']); ?> VND</td>
                <td>
                    <form action="?page=update_cart.php" method="POST">
                        <input type="hidden" name="cart_id" value="<?php echo $row['CartID']; ?>">
                        <input type="number" name="quantity" value="<?php echo $row['Quantity']; ?>" min="1" style="width: 60px;">
                        <button type="submit" class="btn btn-sm btn-warning">Update</button>
                    </form>
                </td>
                <td><?php echo htmlspecialchars($row['ServiceName'] ?? 'None'); ?></td>
                <td><?php echo ($serviceTotal ? htmlspecialchars($serviceTotal) . ' VND' : 'None'); ?></td>
                <td><?php echo $totalItemPrice; ?> VND</td>
                <td>
                    <a href="?page=delete_cart.php&id=<?php echo $row['CartID']; ?>" class="btn btn-sm btn-danger">Remove</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<h3>Total: <?php echo $totalPrice; ?> VND</h3>
<a href="?page=checkout.php" class="btn btn-primary">Checkout</a>
<?php
?>
<hr />