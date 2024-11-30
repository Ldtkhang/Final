<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['id'] == "") {
        $error .= "<li>Please enter ID</li>";
    }
    if ($_POST['name'] == "") {
        $error .= "<li>Please enter Name</li>";
    }
    if ($_POST['price'] == "") {
        $error .= "<li>Please enter Price</li>";
    }
    if ($_POST['type'] == "") {
        $error .= "<li>Please select Type (Dish or Drink)</li>";
    }

    if ($error == "") {
        // Insert to MySQL
        $id = $_POST["id"];
        $name = $_POST["name"];
        $price = $_POST["price"];
        $type = $_POST["type"];
        $filePic = "";

        $sql = "SELECT * FROM dishanddrink WHERE DishanddrinkID='$id'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $filePic = $row['DishanddrinkImage'];

            if (!empty($_FILES['fileimage']['name'])) {
                $pic = $_FILES["fileimage"];
                if ($pic['type'] == "image/jpg" || $pic['type'] == "image/jpeg" || $pic['type'] == "image/png" || $pic['type'] == "image/gif") {
                    // No size limit, directly copy the file
                    copy($pic['tmp_name'], "pimgs/" . $pic['name']);
                    $filePic = $pic['name'];
                } else {
                    $error .= "<li>Invalid image format. Allowed formats: jpg, jpeg, png, gif</li>";
                }
            }

            $sql = "UPDATE dishanddrink SET DishanddrinkName = '$name', DishanddrinkPrice = '$price', DishanddrinkImage = '$filePic', DishanddrinkType = '$type' WHERE DishanddrinkID='$id'";
            mysqli_query($conn, $sql);
            echo '<script>alert("Update dish/drink successful")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=?page=manage_dishanddrink.php"/>';
        } else {
            $error .= "<li>Dish/Drink not found</li>";
        }
    }
} else {
    if (isset($_GET["id"])) {
        $id = "";
        $name = "";
        $price = "";
        $sql = "SELECT * FROM dishanddrink WHERE DishanddrinkID=" . $_GET["id"];
        $results = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($results)) {
            $id = $row['DishanddrinkID'];
            $name = $row['DishanddrinkName'];
            $price = $row["DishanddrinkPrice"];
            $filePic = $row["DishanddrinkImage"];
            $type = $row["DishanddrinkType"];
        }
    }
}
?>
<h2>Update Dish/Drink</h2>
<hr style="background-color: red; height:3px" />
<ul style="color: red;">
    <?php echo $error; ?>
</ul>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="">ID</label>
        <input type="text" class="form-control" name="id" id="" value='<?php echo isset($id) ? $id : ""; ?>' readonly>
    </div>
    <div class="form-group">
        <label for="">Name</label>
        <input type="text" class="form-control" name="name" id="" value="<?php echo isset($name) ? $name : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Price</label>
        <input type="text" class="form-control" name="price" id="" value="<?php echo isset($price) ? $price : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Image</label>
        <input type="file" class="form-control-file" name="fileimage" id="" />
    </div>
    <div class="form-group">
        <label for="">Type</label>
        <select class="form-control" name="type" id="">
            <option value="Dish" <?php echo (isset($type) && $type == 'Dish') ? 'selected' : ''; ?>>Dish</option>
            <option value="Drink" <?php echo (isset($type) && $type == 'Drink') ? 'selected' : ''; ?>>Drink</option>
        </select>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
