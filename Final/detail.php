<?php
$error = "";
if (!isset($_SESSION['usid']) || $_SESSION['usid'] == "") {
    echo '<script>alert("PLEASE LOGIN")</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p class='text-center'>Invalid Product ID.</p>";
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM dishanddrink WHERE DishanddrinkID = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
?>
        <div class="row main">
            <div class="col-md-6 text-center">
                <img class="card-img-top" src="<?php echo 'admin/pimgs/' . htmlspecialchars($row['DishanddrinkImage']); ?>" alt="<?php echo htmlspecialchars($row['DishanddrinkName']); ?>" style="height:350px">
                <hr />
            </div>
            <div class="col-md-6">
                <h2 class="card-title" style="font-size: 24px; margin-bottom: 15px;"><?php echo htmlspecialchars($row['DishanddrinkName']); ?></h2>
                <h3 style="font-size: 20px; margin-bottom: 15px;">Price: <?php echo htmlspecialchars($row['DishanddrinkPrice']); ?> VND</h3>
                <form action="?page=add_cart.php&id=<?php echo $row['DishanddrinkID']; ?>" method="POST">
    <h4 style="font-size: 20px; margin-bottom: 15px;">Select Quantity:</h4>
    <input type="number" name="quantity" min="1" value="1" style="font-size: 18px; margin-bottom: 10px; width: 60px;" />

    <h4 style="font-size: 20px; margin-bottom: 15px;">Select Services (optional):</h4>
    <select name="services" style="font-size: 18px; margin-bottom: 10px;">
        <option value="">No service</option>
        <?php
        $serviceSql = "SELECT * FROM services";
        $serviceResult = mysqli_query($conn, $serviceSql);
        if ($serviceResult && mysqli_num_rows($serviceResult) > 0) {
            while ($serviceRow = mysqli_fetch_array($serviceResult)) {
                ?>
                <option value="<?php echo $serviceRow['ServicesID']; ?>">
                    <?php echo htmlspecialchars($serviceRow['ServiceName']); ?> - <?php echo htmlspecialchars($serviceRow['ServicesPrice']); ?> VND
                </option>
                <?php
            }
        } else {
            echo "<option value=''>No services available</option>";
        }
        ?>
    </select>
    <hr />
    <button type="submit" class="btn btn-primary mt-3">Add to cart</button>
</form>

            </div>
        </div>
<?php
    } else {
        echo "<p class='text-center'>Product not found.</p>";
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement.";
}
?>