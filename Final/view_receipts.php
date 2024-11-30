<?php
if (empty($_SESSION['usname'])) {
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
} else {
    $usid = $_SESSION['usid'];

    $ordersSql = "
        SELECT o.OrderID, r.ReceiptID, r.ReceiptDate, u.UserFullName, u.UserPhone 
        FROM orders o 
        LEFT JOIN receipt r ON o.OrderID = r.OrderID
        LEFT JOIN user u ON o.UserID = u.UserID
        WHERE o.UserID = ?";

    $ordersStmt = $conn->prepare($ordersSql);
    
    if (!$ordersStmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    
    $ordersStmt->bind_param("i", $usid);
    $ordersStmt->execute();
    $ordersResult = $ordersStmt->get_result();

    echo '<div class="container mt-5">';
    echo '<h3 class="text-center mb-4">My Receipts</h3>';

    if (mysqli_num_rows($ordersResult) > 0) {
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-dark">';
        echo '<tr>';
        echo '<th>Receipt ID</th>';
        echo '<th>Receipt Date</th>';
        echo '<th>Full Name</th>';
        echo '<th>Phone</th>';
        echo '<th>Details</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($order = mysqli_fetch_assoc($ordersResult)) {
            $orderID = $order['OrderID'];
            $receiptID = $order['ReceiptID'];
            $receiptDate = $order['ReceiptDate'];
            $userFullName = $order['UserFullName'];
            $userPhone = $order['UserPhone'];

            echo '<tr>';
            echo '<td>' . htmlspecialchars($receiptID) . '</td>';
            echo '<td>' . htmlspecialchars($receiptDate) . '</td>';
            echo '<td>' . htmlspecialchars($userFullName) . '</td>';
            echo '<td>' . htmlspecialchars($userPhone) . '</td>';
            echo '<td>';

            $orderDetailSql = "
                SELECT d.DishanddrinkName, d.DishanddrinkPrice, od.OrderdetailQuantity, 
                       s.ServiceName, s.ServicesPrice, d.DishanddrinkImage 
                FROM orderdetail od 
                LEFT JOIN dishanddrink d ON od.DishanddrinkID = d.DishanddrinkID 
                LEFT JOIN services s ON od.ServicesID = s.ServicesID 
                WHERE od.OrderID = ?";
            $detailStmt = $conn->prepare($orderDetailSql);
            $detailStmt->bind_param("i", $orderID);
            $detailStmt->execute();
            $detailResult = $detailStmt->get_result();

            if (mysqli_num_rows($detailResult) > 0) {
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Dish/Drink</th>';
                echo '<th>Image</th>';
                echo '<th>Price</th>';
                echo '<th>Quantity</th>';
                echo '<th>Service</th>';
                echo '<th>Service Price</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                $totalPrice = 0;

                while ($detail = mysqli_fetch_assoc($detailResult)) {
                    $itemTotal = $detail['DishanddrinkPrice'] * $detail['OrderdetailQuantity'];
                    $serviceTotal = $detail['ServicesPrice'] ?? 0;

                    $totalPrice += $itemTotal + $serviceTotal;

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($detail['DishanddrinkName']) . '</td>';
                    echo '<td><img src="' . htmlspecialchars('admin/pimgs/' . $detail['DishanddrinkImage']) . '" style="width:100px;" /></td>';
                    echo '<td>' . htmlspecialchars($detail['DishanddrinkPrice']) . '</td>';
                    echo '<td>' . htmlspecialchars($detail['OrderdetailQuantity']) . '</td>';
                    echo '<td>' . htmlspecialchars($detail['ServiceName'] ?? 'N/A') . '</td>';
                    echo '<td>' . htmlspecialchars($serviceTotal) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '<div class="text-right"><strong>Total Price (including services): </strong>' . htmlspecialchars($totalPrice) . '</div>';
            } else {
                echo 'No details found.';
            }
            echo '</td>';
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
