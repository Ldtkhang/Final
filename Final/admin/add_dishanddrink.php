<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['name'] == "") {
        $error .= "<li>Please enter Name</li>";
    }
    if ($_POST['price'] == "") {
        $error .= "<li>Please enter Price</li>";
    }
    if ($_FILES['fileimage']['name'] == "") {
        $error .= "<li>Please choose file image</li>";
    }
    if ($_POST['type'] == "") {
        $error .= "<li>Please select Type (Dish or Drink)</li>";
    }

    if ($error == "") {
        $name = $_POST["name"];
        $price = $_POST["price"];
        $pic = $_FILES["fileimage"];
        $type = $_POST["type"];

        if ($pic['type'] == "image/jpg" || $pic['type'] == "image/jpeg" || $pic['type'] == "image/png" || $pic['type'] == "image/gif") {
            copy($pic['tmp_name'], "pimgs/" . $pic['name']);
            $filePic = $pic['name'];

            $sql = "SELECT * FROM dishanddrink WHERE DishanddrinkName='" . $name . "'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO dishanddrink (DishanddrinkName, DishanddrinkPrice, DishanddrinkImage, DishanddrinkType) VALUES ('$name', '$price', '$filePic', '$type')";
                mysqli_query($conn, $sql);
                echo '<script>alert("Add dish/drink successful")</script>';
                echo '<meta http-equiv="refresh" content="0;URL=?page=manage_dishanddrink.php"/>';
            } else {
                $error .= "<li>Duplicate Dish/Drink Name</li>";
            }
        } else {
            $error .= "<li>Invalid image format. Only JPG, JPEG, PNG & GIF are allowed.</li>";
        }
    }
}
?>

<h2>Create a new Dish/Drink</h2>
<hr style="background-color:pink; height:2px;" />
<ul style="color:red">
    <?php
    echo $error;
    ?>
</ul>
<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="">Name</label>
        <input type="text" class="form-control" name="name" id="" placeholder="">
    </div>
    <div class="form-group">
        <label for="">Price</label>
        <input type="text" class="form-control" name="price" id="" placeholder="">
    </div>
    <div class="form-group">
        <label for="">Image</label>
        <input type="file" class="form-control-file" name="fileimage" id="" placeholder="Choose Image">
    </div>
    <div class="form-group">
        <label for="">Type</label>
        <select class="form-control" name="type" id="">
            <option value="">Choose Type</option>
            <option value="Dish">Dish</option>
            <option value="Drink">Drink</option>
        </select>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
</form>
