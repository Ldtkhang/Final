<?php
if (!isset($_SESSION['usname']) || $_SESSION['usname'] == "") {
    echo "<script>alert('Please log in to view your profile.'); window.location.href='?page=login.php';</script>";
    exit();
}

$user_id = $_SESSION['UserID'];

$query = "SELECT * FROM `user` WHERE `UserID` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['password1'] != $_POST['password2']) {
        $error .= "<li>Password and confirm password must be the same</li>";
    }
    if ($_POST['year'] == "0") {
        $error .= "<li>Choose Year of Birth, please</li>";
    }
    if ($_POST['month'] == "0") {
        $error .= "<li>Choose Month of Birth, please</li>";
    }
    if ($_POST['date'] == "0") {
        $error .= "<li>Choose Date of Birth, please</li>";
    }
    if ($_POST['fullname'] == "" || $_POST['email'] == "" || $_POST['address'] == "" || !isset($_POST['gender'])) {
        $error .= "<li>Enter fields with mark (*), please</li>";
    }

    if ($error == "") {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $tel = $_POST['tel'];
        $sex = isset($_POST['gender']) ? $_POST['gender'] : 'Male';
        $date = $_POST['date'];
        $month = $_POST['month'];
        $year = $_POST['year'];
        $birthDate = "$year-$month-$date";
        if ($_POST['password1'] != "") {
            $passwordmd5 = md5($_POST['password1']);
            $updateQuery = "UPDATE `user` SET `UserFullName` = ?, `UserEmail` = ?, `UserAddress` = ?, `UserPhone` = ?, `UserGender` = ?, `UserBirthDate` = ?, `UserPassword` = ? WHERE `UserID` = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sssssssi", $fullname, $email, $address, $tel, $sex, $birthDate, $passwordmd5, $user_id);
        } else {
            $updateQuery = "UPDATE `user` SET `UserFullName` = ?, `UserEmail` = ?, `UserAddress` = ?, `UserPhone` = ?, `UserGender` = ?, `UserBirthDate` = ? WHERE `UserID` = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssssssi", $fullname, $email, $address, $tel, $sex, $birthDate, $user_id);
        }

        if ($updateStmt->execute()) {
            echo '<script>alert("Profile updated successfully!"); window.location.href="?page=profile.php";</script>';
        } else {
            echo '<script>alert("Failed to update profile. Please try again.");</script>';
        }
    }
}
?>

<h2>Edit Profile</h2>
<hr style="background-color: red; height:3px" />

<ul style="color: red;">
    <?php echo $error; ?>
</ul>

<form method="post" action="" class="form-horizontal">
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Username: </label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['UserName']); ?>" readonly />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Password: </label>
        <div class="col-sm-10">
            <input type="password" name="password1" id="txtPass1" class="form-control" placeholder="Enter new password (optional)" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">Confirm Password: </label>
        <div class="col-sm-10">
            <input type="password" name="password2" id="txtPass2" class="form-control" placeholder="Confirm new password" />
        </div>
    </div>
    <div class="form-group">
        <label for="lblFullName" class="col-sm-2 control-label">Full name*: </label>
        <div class="col-sm-10">
            <input type="text" name="fullname" id="txtFullname" value="<?php echo htmlspecialchars($user['UserFullName']); ?>" class="form-control" placeholder="Enter Fullname" required />
        </div>
    </div>
    <div class="form-group">
        <label for="lblEmail" class="col-sm-2 control-label">Email*: </label>
        <div class="col-sm-10">
            <input type="email" name="email" id="txtEmail" value="<?php echo htmlspecialchars($user['UserEmail']); ?>" class="form-control" placeholder="Email" required />
        </div>
    </div>
    <div class="form-group">
        <label for="lblAddress" class="col-sm-2 control-label">Address*: </label>
        <div class="col-sm-10">
            <input type="text" name="address" id="txtAddress" value="<?php echo htmlspecialchars($user['UserAddress']); ?>" class="form-control" placeholder="Address" required />
        </div>
    </div>
    <div class="form-group">
        <label for="lblTelephone" class="col-sm-2 control-label">Telephone*: </label>
        <div class="col-sm-10">
            <input type="text" name="tel" id="txtTel" value="<?php echo htmlspecialchars($user['UserPhone']); ?>" class="form-control" placeholder="Telephone" required />
        </div>
    </div>
    <div class="form-group">
        <label for="lblGender" class="col-sm-2 control-label">Gender*: </label>
        <div class="col-sm-10">
            <label class="radio-inline"><input type="radio" name="gender" value="Male" <?php if ($user['UserGender'] == 'Male') echo 'checked'; ?> />Male</label>
            <label class="radio-inline"><input type="radio" name="gender" value="Female" <?php if ($user['UserGender'] == 'Female') echo 'checked'; ?> />Female</label>
        </div>
    </div>
    <div class="form-group">
        <label for="lblNgaySinh" class="col-sm-2 control-label">Date of Birth*: </label>
        <div class="col-sm-10 input-group">
            <span class="input-group-btn">
                <select name="date" id="slDate" class="form-control">
                    <option value="0">Choose Date</option>
                    <?php
                    for ($i = 1; $i <= 31; $i++) {
                        echo "<option value='$i' " . ($i == date('d', strtotime($user['UserBirthDate'])) ? 'selected' : '') . ">$i</option>";
                    }
                    ?>
                </select>
            </span>
            <span class="input-group-btn">
                <select name="month" id="slMonth" class="form-control">
                    <option value="0">Choose Month</option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        echo "<option value='$i' " . ($i == date('m', strtotime($user['UserBirthDate'])) ? 'selected' : '') . ">$i</option>";
                    }
                    ?>
                </select>
            </span>
            <span class="input-group-btn">
                <select name="year" id="slYear" class="form-control">
                    <option value="0">Choose Year</option>
                    <?php
                    for ($i = 1970; $i <= 2022; $i++) {
                        echo "<option value='$i' " . ($i == date('Y', strtotime($user['UserBirthDate'])) ? 'selected' : '') . ">$i</option>";
                    }
                    ?>
                </select>
            </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Update Profile</button>
        </div>
    </div>
</form>