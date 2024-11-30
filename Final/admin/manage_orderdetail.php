<h3>Manage Order Details</h3>
<hr style="background-color: blue; height:2px">
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Dish and Drink ID</th>
            <th>Services ID</th>
            <th>Quantity</th>
            <th>Dish/Drink Price</th>
            <th>Service Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Query to select all columns from the `orderdetail` table
        $sql = "SELECT * FROM orderdetail";
        $result = mysqli_query($conn, $sql);

        // Loop through each row in the result set and display them in the table
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['OrderID']; ?></td>
                <td><?php echo $row['DishanddrinkID']; ?></td>
                <td><?php echo $row['ServicesID']; ?></td>
                <td><?php echo $row['OrderdetailQuantity']; ?></td>
                <td><?php echo $row['OrderdetailPrice']; ?></td> <!-- Dish/Drink price only -->
                <td><?php echo $row['OrderdetailServicePrice']; ?></td> <!-- Service price only -->
                <td>
                    <!-- Actions for editing and deleting, identified by OrderID and DishanddrinkID -->
                    <a href="?page=update_orderdetail.php&id=<?php echo $row['OrderID']; ?>&dishanddrinkID=<?php echo $row['DishanddrinkID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_orderdetail.php&id=<?php echo $row['OrderID']; ?>&dishanddrinkID=<?php echo $row['DishanddrinkID']; ?>" onclick="return confirm('Are you sure you want to delete this order detail?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
