<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['tableName'] == "") {
        $error .= "<li>Please enter Table Name</li>";
    }
    if ($_POST['areaID'] == "") {
        $error .= "<li>Please select Area ID</li>";
    }

    if ($error == "") {
        $tableName = $_POST["tableName"];
        $areaID = $_POST["areaID"];
        $tableStatus = "Available";

        $sql = "INSERT INTO restaurant_table (TableName, AreaID, TableStatus) VALUES ('$tableName', '$areaID', '$tableStatus')";
        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Add table successful")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=?page=manage_table.php"/>';
        } else {
            $error .= "<li>Error inserting data: " . mysqli_error($conn) . "</li>";
        }
    }
}
?>

<h2>Create a New Table</h2>
<hr style="background-color: pink; height: 2px;" />
<ul style="color:red">
    <?php
    echo $error;
    ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Table Name</label>
        <input type="text" class="form-control" name="tableName" id="" placeholder="">
    </div>
    <div class="form-group">
        <label for="">Area ID</label>
        <select class="form-control" name="areaID" id="">
            <option value="">Select Area</option>
            <?php
            // Query to select all areas
            $sql = "SELECT * FROM area";
            $result = mysqli_query($conn, $sql);

            // Fetch each area and create an option for the select
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['AreaID'] . '">' . $row['AreaID'] . ' - ' . $row['AreaName'] . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="">Table Status</label>
        <select class="form-control" name="tableStatus" id="">
            <option value="">Select Status</option>
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
</form>
