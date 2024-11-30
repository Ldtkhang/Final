<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['areaName'] == "") {
        $error .= "<li>Please enter Area Name</li>";
    }

    if ($error == "") {
        $id = $_GET['id'];
        $areaName = $_POST['areaName'];

        $sql = "SELECT * FROM area WHERE AreaID='" . $id . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
         
            $sql = "UPDATE area SET 
                        AreaName = '$areaName' 
                    WHERE AreaID = '$id'";
            if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Update successful")</script>';
                echo '<meta http-equiv="refresh" content="0;URL=?page=manage_area.php"/>';
            } else {
                $error .= "<li>Error updating data: " . mysqli_error($conn) . "</li>";
            }
        } else {
            $error .= "<li>Area not found</li>";
        }
    }
} else {
    if (isset($_GET["id"])) {
        $areaName = "";

        $sql = "SELECT * FROM area WHERE AreaID='" . $_GET["id"] . "'";
        $results = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_array($results)) {
            $areaID = $row["AreaID"];
            $areaName = $row["AreaName"];
        }
    }
}
?>

<h2>Update Area Information</h2>
<hr style="background-color: red; height: 2px;" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Area ID</label>
        <!-- Display Area ID as read-only -->
        <input type="text" class="form-control" name="areaID" value="<?php echo isset($areaID) ? $areaID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Area Name</label>
        <input type="text" class="form-control" name="areaName" value="<?php echo isset($areaName) ? $areaName : ''; ?>">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
