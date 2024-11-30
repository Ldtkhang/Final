<?php

$orderID = $_GET['id'];
$dishanddrinkID = $_GET['dishanddrinkID'];

if (isset($orderID) && isset($dishanddrinkID)) {
    $sql = "DELETE FROM orderdetail WHERE OrderID = $orderID AND DishanddrinkID = $dishanddrinkID";
   
    if (mysqli_query($conn, $sql)) {
        header("Location: $urladmin?page=manage_orderdetail.php&status=success");
        exit();
    } else {
        header("Location: $urladmin?page=manage_orderdetail.php&status=error");
        exit();
    }
} else {
    header("Location: $urladmin?page=manage_orderdetail.php&status=invalid");
    exit();
}