<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['name'] == "") {
        $error .= "<li>Please enter Area Name</li>";
    }

    if ($error == "") {
        $name = $_POST["name"];

        $sql = "SELECT * FROM area WHERE AreaName = '$name'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            $error .= "<li>Error checking for duplicates: " . mysqli_error($conn) . "</li>";
        } else {
            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO area (AreaName) VALUES ('$name')";
                if (mysqli_query($conn, $sql)) {
                    echo '<script>alert("Add area successful")</script>';
                    echo '<meta http-equiv="refresh" content="0;URL=?page=manage_area.php"/>';
                } else {
                    $error .= "<li>Error inserting data: " . mysqli_error($conn) . "</li>";
                }
            } else {
                $error .= "<li>Area with this name already exists</li>";
            }
        }
    }
}
?>

<h2>Create a new Area</h2>
<hr style="background-color: pink; height: 2px;" />
<ul style="color:red">
    <?php
    echo $error;
    ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Area Name</label>
        <input type="text" class="form-control" name="name" id="" placeholder="">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
</form>
