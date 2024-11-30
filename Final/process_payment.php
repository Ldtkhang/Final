<?php
if (!isset($_SESSION['usid'])) {
    echo '<script>alert("Please login to process payment.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit;
}

// Process the payment if the request method is POST and total_price is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['total_price'])) {
    $userID = $_SESSION['usid'];
    $totalPrice = floatval($_POST['total_price']);

    // Step 1: Check if there is any OrderID in "Pending" status for this user
    $pendingOrderSql = "SELECT OrderID FROM orders WHERE UserID = ? AND OrdersStatus = 'Pending' ORDER BY OrderID DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $pendingOrderSql);
    if ($stmt === false) {
        die('Error preparing statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $pendingOrderID);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Check if a pending order exists
    if (!$pendingOrderID) {
        echo '<script>alert("Please select your area and table to continue with the payment.");</script>';
        echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
        exit; // Stop further processing
    }

    // Use the existing pending order for payment
    $orderID = $pendingOrderID;

    // Step 2: Insert items from the cart into orderdetail for this OrderID
    $cartSql = "SELECT 
                    c.DishanddrinkID, 
                    c.ServicesID, 
                    c.Quantity, 
                    d.DishanddrinkPrice, 
                    s.ServicesPrice
                FROM cart c
                JOIN dishanddrink d ON c.DishanddrinkID = d.DishanddrinkID
                LEFT JOIN services s ON c.ServicesID = s.ServicesID
                WHERE c.UserID = ?";
    $cartStmt = mysqli_prepare($conn, $cartSql);
    if ($cartStmt === false) {
        die('Error preparing cart statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($cartStmt, "i", $userID);
    mysqli_stmt_execute($cartStmt);
    $cartResult = mysqli_stmt_get_result($cartStmt);

    // Step 3: Add each item from the cart into orderdetail table
    while ($row = mysqli_fetch_assoc($cartResult)) {
        $dishanddrinkID = $row['DishanddrinkID'];
        $servicesID = $row['ServicesID'];
        $orderdetailQuantity = $row['Quantity'];
        $orderdetailPrice = $row['DishanddrinkPrice']; // Dish or drink price without service
        $orderdetailServicePrice = $row['ServicesPrice'] ?? 0; // Only service price

        $insertDetailSql = "INSERT INTO orderdetail (OrderID, DishanddrinkID, ServicesID, OrderdetailQuantity, OrderdetailServicePrice, OrderdetailPrice) 
                            VALUES (?, ?, ?, ?, ?, ?)";
        $detailStmt = mysqli_prepare($conn, $insertDetailSql);
        if ($detailStmt === false) {
            die('Error preparing detail statement: ' . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($detailStmt, "iiidii", $orderID, $dishanddrinkID, $servicesID, $orderdetailQuantity, $orderdetailServicePrice, $orderdetailPrice);
        mysqli_stmt_execute($detailStmt);
        mysqli_stmt_close($detailStmt);
    }
    mysqli_stmt_close($cartStmt);

    // Step 4: Update the order status to Processing
    $updateOrderStatusSql = "UPDATE orders SET OrdersStatus = 'Processing' WHERE OrderID = ?";
    $updateStmt = mysqli_prepare($conn, $updateOrderStatusSql);
    if ($updateStmt === false) {
        die('Error preparing update statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($updateStmt, "i", $orderID);
    mysqli_stmt_execute($updateStmt);
    mysqli_stmt_close($updateStmt);

    // Step 5: Delete all items from the cart after successful payment
    $deleteSql = "DELETE FROM cart WHERE UserID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    if ($deleteStmt === false) {
        die('Error preparing delete statement: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($deleteStmt, "i", $userID);
    mysqli_stmt_execute($deleteStmt);
    mysqli_stmt_close($deleteStmt);

    // Final success message
    echo '<script>alert("Payment successful! Thank you for your purchase.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
} else {
    echo '<script>alert("Invalid payment request.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
}
?>