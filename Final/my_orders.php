<?php
if (empty($_SESSION['usname'])) {
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
} else {
    $usid = $_SESSION['usid'];

    $ordersSql = "
        SELECT o.OrderID, o.UserID, o.OrdersDate, o.OrdersStatus, a.AreaName, t.TableName 
        FROM orders o 
        LEFT JOIN area a ON o.AreaID = a.AreaID 
        LEFT JOIN restaurant_table t ON o.TableID = t.TableID 
        WHERE o.UserID = ?";
    $ordersStmt = $conn->prepare($ordersSql);
    
    if (!$ordersStmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    
    $ordersStmt->bind_param("i", $usid);
    $ordersStmt->execute();
    $ordersResult = $ordersStmt->get_result();

    echo '<div class="container mt-5">';
    echo '<h3 class="text-center mb-4">My Orders</h3>';

    if (mysqli_num_rows($ordersResult) > 0) {
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>Order ID</th>';
        echo '<th>User ID</th>';
        echo '<th>Orders Date</th>';
        echo '<th>Status</th>';
        echo '<th>Area Name</th>';
        echo '<th>Table Name</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($order = mysqli_fetch_assoc($ordersResult)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($order['OrderID']) . '</td>';
            echo '<td>' . htmlspecialchars($order['UserID']) . '</td>';
            echo '<td>' . htmlspecialchars($order['OrdersDate']) . '</td>';
            echo '<td>' . htmlspecialchars($order['OrdersStatus']) . '</td>';
            echo '<td>' . htmlspecialchars($order['AreaName']) . '</td>';
            echo '<td>' . htmlspecialchars($order['TableName']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-warning" role="alert">No orders found.</div>';
    }
    echo '</div>';
}
?>
