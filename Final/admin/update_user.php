<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    // Validate form inputs
    if ($_POST['fullname'] == "") {
        $error .= "<li>Please enter Name</li>";
    }
    if ($_POST['address'] == "") {
        $error .= "<li>Please enter Address</li>";
    }
    if ($_POST['email'] == "") {
        $error .= "<li>Please enter Email</li>";
    }
    if ($_POST['tel'] == "") {
        $error .= "<li>Please enter Phone</li>";
    }
    if ($_POST['gender'] == "") {
        $error .= "<li>Please enter Gender</li>";
    }
    if ($_POST['year'] == "" || $_POST['month'] == "" || $_POST['date'] == "") {
        $error .= "<li>Please enter Birth Date</li>";
    }

    if ($error == "") {
        // Update user information in MySQL
        $id = $_GET['id'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $gender = ($_POST['gender'] == 'Male' || $_POST['gender'] == 'male') ? 'Male' : 'Female';

        // Construct the birthdate from the input fields
        $year = $_POST['year'];
        $month = $_POST['month'];
        $date = $_POST['date'];
        $birthdate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($date, 2, '0', STR_PAD_LEFT);

        $sql = "SELECT * FROM user WHERE UserID='" . $id . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Update query
            $sql = "UPDATE user SET UserFullName = '$fullname', UserEmail = '$email', UserAddress = '$address', UserPhone = '$tel', UserGender = '$gender', UserBirthDate = '$birthdate' WHERE UserID = '$id'";
            mysqli_query($conn, $sql);
            echo '<script>alert("Update user successful")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=?page=manage_user.php"/>';
        } else {
            $error .= "<li>User not found</li>";
        }
    }
} else {
    // Pre-fill the form with existing user data
    if (isset($_GET["id"])) {
        $fullname = "";
        $email = "";
        $address = "";
        $tel = "";
        $gender = "";
        $year = "";
        $month = "";
        $date = "";
        
        $sql = "SELECT * FROM user WHERE UserID=" . $_GET["id"];
        $results = mysqli_query($conn, $sql);
        
        while ($row = mysqli_fetch_array($results)) {
            $fullname = $row["UserFullName"];
            $email = $row["UserEmail"];
            $address = $row["UserAddress"];
            $tel = $row["UserPhone"];
            $gender = $row['UserGender'];
            $birthdate = $row["UserBirthDate"];
            
            // Split the birthdate into year, month, and day
            $year = date('Y', strtotime($birthdate));
            $month = date('m', strtotime($birthdate));
            $date = date('d', strtotime($birthdate));
        }
    }
}
?>

<h2>Update User Information</h2>
<hr style="background-color: red; height:2px" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Full Name</label>
        <input type="text" class="form-control" name="fullname" id="" value="<?php echo isset($fullname) ? $fullname : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Email</label>
        <input type="text" class="form-control" name="email" id="" value="<?php echo isset($email) ? $email : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Address</label>
        <input type="text" class="form-control" name="address" id="" value="<?php echo isset($address) ? $address : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Telephone</label>
        <input type="text" class="form-control" name="tel" id="" value="<?php echo isset($tel) ? $tel : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Gender</label>
        <select name="gender" class="form-control">
            <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>
    </div>
    <div class="form-group">
        <label for="">Year</label>
        <input type="text" class="form-control" name="year" id="" value="<?php echo isset($year) ? $year : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Month</label>
        <input type="text" class="form-control" name="month" id="" value="<?php echo isset($month) ? $month : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Date</label>
        <input type="text" class="form-control" name="date" id="" value="<?php echo isset($date) ? $date : ''; ?>">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
