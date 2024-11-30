<?php

$id = $_GET['id'];

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Delete all related order details
    $sqlOrderDetail = "DELETE FROM orderdetail WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlOrderDetail);

    // Delete related receipts
    $sqlReceipt = "DELETE FROM receipt WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlReceipt);

    // Delete the order
    $sqlOrder = "DELETE FROM orders WHERE OrderID = " . $id;
    mysqli_query($conn, $sqlOrder);

    // Commit transaction
    mysqli_commit($conn);

    // Redirect to admin page
    header("Location: $urladmin?page=$order");
    exit(); // Ensure the script stops executing after the redirect
} catch (Exception $e) {
    // If an error occurs, rollback the transaction
    mysqli_rollback($conn);
    echo "An error occurred: " . $e->getMessage(); // Or handle the error as you wish
}
