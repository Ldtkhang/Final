<?php

$id = $_GET['id'];

mysqli_begin_transaction($conn);

try {
    $sqlOrderDetail = "DELETE FROM orderdetail WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlOrderDetail);

    $sqlReceipt = "DELETE FROM receipt WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlReceipt);

    $sqlOrder = "DELETE FROM orders WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlOrder);

    mysqli_commit($conn);

    header("Location: $urladmin?page=$order");
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "An error occurred: " . $e->getMessage();
}
