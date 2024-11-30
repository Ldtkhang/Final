<h3>Manage Orders</h3>
<hr style="background-color: blue; height:2px">
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>UserID</th>
            <th>Orders Date</th>
            <th>Orders Status</th>
            <th>AreaID</th>
            <th>Table ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM orders";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['OrderID']; ?></td>
                <td><?php echo $row['UserID']; ?></td>
                <td><?php echo $row['OrdersDate']; ?></td>
                <td><?php echo $row['OrdersStatus']; ?></td>
                <td><?php echo $row['AreaID']; ?></td>
                <td><?php echo $row['TableID']; ?></td>
                <td>
                    <a href="?page=update_orders.php&id=<?php echo $row['OrderID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_orders.php&id=<?php echo $row['OrderID']; ?>" onclick="return confirm('Are you sure you want to delete this order?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
