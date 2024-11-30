<?php
if (empty($_SESSION['usname'])) {
    echo '<script>alert("PLEASE LOGIN TO UPDATE YOUR CART")</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit();
}

$usid = $_SESSION['usid'];

$cartSql = "
    SELECT a.*, b.DishanddrinkName, b.DishanddrinkPrice, b.DishanddrinkImage, 
           c.ServiceName, c.ServicesPrice 
    FROM cart a
    LEFT JOIN dishanddrink b ON a.DishanddrinkID = b.DishanddrinkID 
    LEFT JOIN services c ON a.ServicesID = c.ServicesID 
    WHERE a.UserID = ?";
$cartStmt = $conn->prepare($cartSql);
$cartStmt->bind_param("i", $usid);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

$servicesSql = "SELECT * FROM services";
$servicesResult = mysqli_query($conn, $servicesSql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['service'] as $dishanddrinkID => $newServiceID) {
        $newServiceID = intval($newServiceID);
        $dishanddrinkID = intval($dishanddrinkID);

        $updateSql = "UPDATE cart SET ServicesID = ? WHERE UserID = ? AND DishanddrinkID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iii", $newServiceID, $usid, $dishanddrinkID);

        if (!$updateStmt->execute()) {
            echo "Error updating service for Dish/Drink ID: " . $dishanddrinkID . " - " . mysqli_error($conn);
        }
        $updateStmt->close();
    }
    echo '<script>alert("Services updated successfully!")</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=shoppingcart.php"/>';
}

?>
<div class="container">
    <h3>Update Services for Your Cart Items</h3>
    <form action="" method="POST">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Dish/Drink Name</th>
                    <th>Current Service</th>
                    <th>Select New Service</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($cartRow = mysqli_fetch_array($cartResult)) {
                    $servicesResult->data_seek(0);
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cartRow['DishanddrinkName']); ?></td>
                        <td>
                            <?php echo !empty($cartRow['ServiceName']) ? htmlspecialchars($cartRow['ServiceName']) : 'No Service'; ?>
                        </td>
                        <td>
                            <?php while ($serviceRow = mysqli_fetch_array($servicesResult)) { ?>
                                <div style="font-size: 16px; margin-bottom: 5px;">
                                    <input type="radio" name="service[<?php echo $cartRow['DishanddrinkID']; ?>]"
                                        value="<?php echo $serviceRow['ServicesID']; ?>"
                                        <?php echo $cartRow['ServicesID'] == $serviceRow['ServicesID'] ? 'checked' : ''; ?> />
                                    <?php echo htmlspecialchars($serviceRow['ServiceName']); ?> - <?php echo htmlspecialchars($serviceRow['ServicesPrice']); ?> VND
                                </div>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary mt-3">Update Services</button>
        <hr />
    </form>
</div>
<?php
$cartStmt->close();
?>