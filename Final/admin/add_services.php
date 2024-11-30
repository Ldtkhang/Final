<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    // Validate form inputs
    if ($_POST['id'] == "") {
        $error .= "<li>Please enter Service ID</li>";
    }
    if ($_POST['name'] == "") {
        $error .= "<li>Please enter Service Name</li>";
    }
    if ($_POST['price'] == "" || !is_numeric($_POST['price'])) {
        $error .= "<li>Please enter a valid Service Price</li>";
    }

    // If no errors, proceed to add the service
    if ($error == "") {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $price = $_POST["price"];

        // Include your database connection file
        include 'db.php'; // Make sure to replace this with the correct path to your db.php

        // Check for duplicate service
        $sql = "SELECT * FROM services WHERE ServicesID = '$id'";
        $result = mysqli_query($conn, $sql);

        // Check if the query was successful
        if (!$result) {
            $error .= "<li>Error checking for duplicates: " . mysqli_error($conn) . "</li>";
        } else {
            if (mysqli_num_rows($result) == 0) {
                // Insert the new service into the database
                $sql = "INSERT INTO services (ServicesID, ServiceName, ServicesPrice) VALUES ('$id', '$name', '$price')";
                if (mysqli_query($conn, $sql)) {
                    echo '<script>alert("Add service successful")</script>';
                    echo '<meta http-equiv="refresh" content="0;URL=?page=manage_services.php"/>';
                } else {
                    $error .= "<li>Error inserting data: " . mysqli_error($conn) . "</li>";
                }
            } else {
                $error .= "<li>Service with this ID already exists</li>";
            }
        }
    }
}
?>

<h2>Create a new Service</h2>
<hr style="background-color: pink; height: 2px;" />
<ul style="color:red">
    <?php
    echo $error;
    ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Service ID</label>
        <input type="text" class="form-control" name="id" id="" placeholder="">
    </div>
    <div class="form-group">
        <label for="">Service Name</label>
        <input type="text" class="form-control" name="name" id="" placeholder="">
    </div>
    <div class="form-group">
        <label for="">Service Price</label>
        <input type="text" class="form-control" name="price" id="" placeholder="">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
</form>