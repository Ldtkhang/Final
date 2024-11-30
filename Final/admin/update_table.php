<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['tableName'] == "") {
        $error .= "<li>Please enter Table Name</li>";
    }
    if ($_POST['areaID'] == "") {
        $error .= "<li>Please select Area ID</li>";
    }
    if ($_POST['tableStatus'] == "") {
        $error .= "<li>Please select Table Status</li>";
    }

    if ($error == "") {
        $tableID = $_GET['id'];
        $tableName = $_POST['tableName'];
        $areaID = $_POST['areaID'];
        $tableStatus = $_POST['tableStatus'];

        $sql = "SELECT * FROM restaurant_table WHERE TableID='$tableID'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Update query
            $sql = "UPDATE restaurant_table SET 
                        TableName = '$tableName', 
                        AreaID = '$areaID', 
                        TableStatus = '$tableStatus' 
                    WHERE TableID = '$tableID'";
            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Update successful")</script>';
                echo '<meta http-equiv="refresh" content="0;URL=?page=manage_table.php"/>';
            } else {
                $error .= "<li>Error updating data: " . mysqli_error($conn) . "</li>";
            }
        } else {
            $error .= "<li>Table not found</li>";
        }
    }
} else {
    if (isset($_GET["id"])) {
        $tableID = "";
        $tableName = "";
        $areaID = "";
        $tableStatus = "";

        $sql = "SELECT * FROM restaurant_table WHERE TableID=" . $_GET["id"];
        $results = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($results)) {
            $tableID = $row["TableID"];
            $tableName = $row["TableName"];
            $areaID = $row["AreaID"];
            $tableStatus = $row["TableStatus"];
        }
    }
}
?>

<h2>Update Table Information</h2>
<hr style="background-color: red; height: 2px;" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <!-- Hidden field for Table ID -->
    <input type="hidden" name="tableID" value="<?php echo isset($tableID) ? $tableID : ''; ?>">

    <div class="form-group">
        <label for="">Table ID</label>
        <input type="text" class="form-control" name="tableIDDisplay" value="<?php echo isset($tableID) ? $tableID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Table Name</label>
        <input type="text" class="form-control" name="tableName" value="<?php echo isset($tableName) ? $tableName : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Area ID</label>
        <select class="form-control" name="areaID">
            <option value="">Select Area</option>
            <?php
            $sql = "SELECT * FROM area";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                // Set selected if the area matches
                $selected = ($row['AreaID'] == $areaID) ? 'selected' : '';
                echo '<option value="' . $row['AreaID'] . '" ' . $selected . '>' . $row['AreaID'] . ' - ' . $row['AreaName'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="">Table Status</label>
        <select class="form-control" name="tableStatus">
            <option value="">Select Status</option>
            <option value="Available" <?php echo ($tableStatus == "Available") ? 'selected' : ''; ?>>Available</option>
            <option value="Unavailable" <?php echo ($tableStatus == "Unavailable") ? 'selected' : ''; ?>>Unavailable</option>
        </select>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
