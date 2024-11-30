<?php
require '../vendor/PHPMailer-master/src/Exception.php';
require '../vendor/PHPMailer-master/src/PHPMailer.php';
require '../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$error = "";
if (isset($_POST['btnSubmit'])) {
    if ($_POST['ordersDate'] == "") {
        $error .= "<li>Please enter Order Date</li>";
    }
    if ($_POST['ordersStatus'] == "") {
        $error .= "<li>Please enter Order Status</li>";
    }

    if ($error == "") {
        $id = $_GET['id'];
        $ordersDate = $_POST['ordersDate'];
        $ordersStatus = $_POST['ordersStatus'];

        $sql = "SELECT * FROM orders WHERE OrderID='" . $id . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $order = mysqli_fetch_assoc($result);
            $userID = $order['UserID'];

            $userSql = "SELECT UserEmail, UserFullName FROM user WHERE UserID='" . $userID . "'";
            $userResult = mysqli_query($conn, $userSql);
            $userRow = mysqli_fetch_assoc($userResult);
            $userEmail = $userRow['UserEmail'];
            $userFullName = $userRow['UserFullName'];

            $sql = "UPDATE orders SET 
                        OrdersDate = '$ordersDate', 
                        OrdersStatus = '$ordersStatus'
                    WHERE OrderID = '$id'";
            mysqli_query($conn, $sql);
            echo '<script>alert("Update successful")</script>';
            
            if ($ordersStatus == 'Completed') {
                // Gửi email
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'khangldtgcc200376@fpt.edu.vn';
                $mail->Password = 'ucth tcqd tqdz nupx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('khangldtgcc200376@fpt.edu.vn', 'TK Restaurant');
                $mail->addAddress($userEmail);

                $mail->isHTML(true);

                $bodyContent .= '<p>Dear ' . htmlspecialchars($userFullName) . ',</p>';
                $bodyContent .= '<p>Thank you for your order with ID <strong>' . $id . '</strong>. We are pleased to inform you that your order has been successfully completed.</p>';
                $bodyContent .= '<p>Order Date: ' . $ordersDate . '</p>';
                $bodyContent .= '<p>We appreciate your choice to dine with us and look forward to serving you again!</p>';
                $bodyContent .= '<p>Best regards,<br>TK Restaurant Team</p>';

                $mail->Subject = 'Thank You for Your Order at TK Restaurant!';
                $mail->Body    = $bodyContent;

                try {
                    $mail->send();
                } catch (Exception $e) {
                    echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
                }
            }

            echo '<meta http-equiv="refresh" content="0;URL=?page=manage_orders.php"/>';
        } else {
            $error .= "<li>Order not found</li>";
        }
    }
} else {
    if (isset($_GET["id"])) {
        $orderID = "";
        $userID = "";
        $ordersDate = "";
        $ordersStatus = "";
        $areaID = "";
        $tableID = "";

        $sql = "SELECT * FROM orders WHERE OrderID=" . $_GET["id"];
        $results = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($results)) {
            $orderID = $row["OrderID"];
            $userID = $row["UserID"]; 
            $ordersDate = $row["OrdersDate"];
            $ordersStatus = $row["OrdersStatus"];
            $areaID = $row["AreaID"]; 
            $tableID = $row["TableID"];
        }
    }
}
?>

<h2>Update Order Information</h2>
<hr style="background-color: red; height:2px" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Order ID</label>
        <input type="text" class="form-control" name="orderID" value="<?php echo isset($orderID) ? $orderID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">User ID</label>
        <input type="text" class="form-control" name="userID" value="<?php echo isset($userID) ? $userID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Order Date</label>
        <input type="date" class="form-control" name="ordersDate" value="<?php echo isset($ordersDate) ? $ordersDate : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Order Status</label>
        <select class="form-control" name="ordersStatus">
            <option value="">-- Select Status --</option>
            <option value="Pending" <?php echo (isset($ordersStatus) && $ordersStatus == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Processing" <?php echo (isset($ordersStatus) && $ordersStatus == 'Processing') ? 'selected' : ''; ?>>Processing</option>
            <option value="Completed" <?php echo (isset($ordersStatus) && $ordersStatus == 'Completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="Canceled" <?php echo (isset($ordersStatus) && $ordersStatus == 'Canceled') ? 'selected' : ''; ?>>Canceled</option>
        </select>
    </div>
    <div class="form-group">
        <label for="">Area ID</label>
        <input type="text" class="form-control" name="areaID" value="<?php echo isset($areaID) ? $areaID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Table ID</label>
        <input type="text" class="form-control" name="tableID" value="<?php echo isset($tableID) ? $tableID : ''; ?>" readonly>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>