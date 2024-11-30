<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
  // Validate form inputs
  if ($_POST['id'] == "") {
    $error .= "<li>Please enter ID</li>";
  }
  if ($_POST['username'] == "") {
    $error .= "<li>Please enter Username</li>";
  }
  if ($_POST['password'] == "") {
    $error .= "<li>Please enter Password</li>";
  }
  if ($_POST['confirm_password'] == "") {
    $error .= "<li>Please confirm Password</li>";
  } else if ($_POST['password'] !== $_POST['confirm_password']) {
    $error .= "<li>Passwords do not match</li>";
  }
  if ($_POST['email'] == "") {
    $error .= "<li>Please enter Email</li>";
  }
  if ($_POST['fullname'] == "") {
    $error .= "<li>Please enter Full Name</li>";
  }
  if ($_POST['address'] == "") {
    $error .= "<li>Please enter Address</li>";
  }
  if ($_POST['phone'] == "") {
    $error .= "<li>Please enter Phone Number</li>";
  }
  if ($_POST['birthdate'] == "") {
    $error .= "<li>Please enter Birth Date</li>";
  }
  if ($_POST['role'] == "") {
    $error .= "<li>Please select Role</li>";
  }
  if ($_POST['gender'] == "") {
    $error .= "<li>Please select Gender</li>";
  }

  // If no errors, proceed to add the user
  if ($error == "") {
    $id = $_POST["id"];
    $username = $_POST["username"];
    $password = $_POST["password"]; // The original password
    $email = $_POST["email"];
    $fullname = $_POST["fullname"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $birthdate = $_POST["birthdate"];
    $role = $_POST["role"];
    $gender = $_POST["gender"]; // Get gender value

    // Check for duplicate username or email
    $sql = "SELECT * FROM user WHERE UserName = '$username' OR UserEmail = '$email'";
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if (!$result) {
      $error .= "<li>Error checking for duplicates: " . mysqli_error($conn) . "</li>";
    } else {
      if (mysqli_num_rows($result) == 0) {
        // Hash the password before inserting
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $sql = "INSERT INTO user (UserID, UserName, UserPassword, UserEmail, UserGender, UserFullName, UserAddress, UserPhone, UserBirthDate, UserRole) 
                VALUES ('$id', '$username', '$hashedPassword', '$email', '$gender', '$fullname', '$address', '$phone', '$birthdate', '$role')";

        if (mysqli_query($conn, $sql)) {
          echo '<script>alert("Add user successful")</script>';
          echo '<meta http-equiv="refresh" content="0;URL=?page=manage_user.php"/>';
        } else {
          $error .= "<li>Error inserting data: " . mysqli_error($conn) . "</li>";
        }
      } else {
        $error .= "<li>User with this username or email already exists</li>";
      }
    }
  }
}
?>

<h2>Create a new User</h2>
<hr style="background-color: pink; height: 2px;" />
<ul style="color:red">
  <?php
  echo $error;
  ?>
</ul>
<form action="" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="">ID</label>
    <input type="text" class="form-control" name="id" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Username</label>
    <input type="text" class="form-control" name="username" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Password</label>
    <input type="password" class="form-control" name="password" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Confirm Password</label>
    <input type="password" class="form-control" name="confirm_password" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Email</label>
    <input type="email" class="form-control" name="email" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Full Name</label>
    <input type="text" class="form-control" name="fullname" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Address</label>
    <input type="text" class="form-control" name="address" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Phone</label>
    <input type="text" class="form-control" name="phone" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Birth Date</label>
    <input type="date" class="form-control" name="birthdate" id="" placeholder="">
  </div>
  <div class="form-group">
    <label for="">Gender</label>
    <select class="form-control" name="gender" id="">
      <option value="">Select Gender</option>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="Other">Other</option>
    </select>
  </div>
  <div class="form-group">
    <label for="">Role</label>
    <select class="form-control" name="role" id="">
      <option value='Admin'>Admin</option>
      <option value='Employee'>Employee</option>
      <option value='Customer'>Customer</option>
    </select>
  </div>
  <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
</form>
