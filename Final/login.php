<?php
$error = "";
if (isset($_POST['btnSubmit'])) {
    // Validate inputs
    if ($_POST['usname'] == "") {
        $error .= "<li>Please enter Username</li>";
    }
    if ($_POST['password'] == "") {
        $error .= "<li>Please enter Password</li>";
    }

    // If no errors, process the login
    if ($error == "") {
        $username = $_POST['usname'];
        $password = $_POST['password'];
        $passwordmd5 = md5($password); // Hash the password

        // Check customer account
        $sql = "SELECT * FROM user WHERE UserName='" . mysqli_real_escape_string($conn, $username) . "' AND UserPassword='" . $passwordmd5 . "' AND UserRole='customer'";
        $result = mysqli_query($conn, $sql);

        // Check if login was successful
        if (mysqli_num_rows($result) == 1) {
            $_SESSION["usname"] = $username; // Store username in session

            while ($row = mysqli_fetch_array($result)) {
                $_SESSION["UserID"] = $row['UserID'];
                $_SESSION["UserRole"] = $row['UserRole'];
                $_SESSION["usid"] = $row['UserID']; // Store user ID in session
            }

            echo '<script>alert("YOU LOGGED IN SUCCESSFULLY")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
        } else {
            // Check for admin account
            $sql = "SELECT * FROM user WHERE UserName='" . mysqli_real_escape_string($conn, $username) . "' AND UserPassword='" . $passwordmd5 . "' AND UserRole='Admin'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $_SESSION["usname"] = $username; // Store username in session

                while ($row = mysqli_fetch_array($result)) {
                    $_SESSION["UserID"] = $row['UserID'];
                    $_SESSION["UserRole"] = $row['UserRole'];
                    $_SESSION["usid"] = $row['UserID']; // Store user ID in session
                }

                echo '<script>alert("YOU LOGGED IN SUCCESSFULLY")</script>';
                header("Location: $urladmin?page=$home");
            } else {
                $error .= "<li>LOGIN FAILED</li>"; // Invalid credentials
            }
        }
    }
}

// If register button is clicked, redirect to register page
if (isset($_POST['btnRegister'])) {
    echo '<meta http-equiv="refresh" content="0;URL=?page=register.php"/>';
}
?>

<?php if ($error): ?>
    <ul class="alert alert-danger">
        <?php echo $error; ?>
    </ul>
<?php endif; ?>

<div class="col-sm-4">
    <form name="form1" action="" method="post">
        <div class="form-group">
            <label for="">Username</label>
            <input type="text" class="form-control" name="usname" id="" aria-describedby="helpId" placeholder="Enter your username">
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" class="form-control" name="password" id="" placeholder="Enter your password">
        </div>
        <button type="submit" name="btnSubmit" class="btn btn-primary" onclick="formValid();">Sign in</button>
        <hr />
    </form>
</div>
