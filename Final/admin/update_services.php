<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    // Validate form inputs
    if ($_POST['serviceID'] == "") {
        $error .= "<li>Please enter Service ID</li>";
    }
    if ($_POST['serviceName'] == "") {
        $error .= "<li>Please enter Service Name</li>";
    }
    if ($_POST['servicePrice'] == "" || !is_numeric($_POST['servicePrice'])) {
        $error .= "<li>Please enter a valid Service Price</li>";
    }

    if ($error == "") {
        // Update service information in MySQL
        $id = $_GET['id'];
        $serviceID = $_POST['serviceID'];
        $serviceName = $_POST['serviceName'];
        $servicePrice = $_POST['servicePrice'];

        $sql = "SELECT * FROM services WHERE ServicesID='" . $id . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Update query
            $sql = "UPDATE services SET 
                        ServicesID = '$serviceID', 
                        ServiceName = '$serviceName', 
                        ServicesPrice = '$servicePrice' 
                    WHERE ServicesID = '$id'";
            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Update successful")</script>';
                echo '<meta http-equiv="refresh" content="0;URL=?page=manage_services.php"/>';
            } else {
                $error .= "<li>Error updating data: " . mysqli_error($conn) . "</li>";
            }
        } else {
            $error .= "<li>Service not found</li>";
        }
    }
} else {
    // Pre-fill the form with existing service data
    if (isset($_GET["id"])) {
        $serviceID = "";
        $serviceName = "";
        $servicePrice = "";

        $sql = "SELECT * FROM services WHERE ServicesID=" . $_GET["id"];
        $results = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_array($results)) {
            $serviceID = $row["ServicesID"];
            $serviceName = $row["ServiceName"];
            $servicePrice = $row["ServicesPrice"];
        }
    }
}
?>

<h2>Update Service Information</h2>
<hr style="background-color: red; height:2px" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Service ID</label>
        <input type="text" class="form-control" name="serviceID" value="<?php echo isset($serviceID) ? $serviceID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Service Name</label>
        <input type="text" class="form-control" name="serviceName" value="<?php echo isset($serviceName) ? $serviceName : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Service Price</label>
        <input type="text" class="form-control" name="servicePrice" value="<?php echo isset($servicePrice) ? $servicePrice : ''; ?>">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
