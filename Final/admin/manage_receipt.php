<h3>Receipt Management</h3>
<hr style="background-color: blue; height:2px">
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>Receipt ID</th>
            <th>Order ID</th>
            <th>Receipt Date</th>
            <th>Receipt Quantity</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM receipt";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['ReceiptID']; ?></td>
                <td><?php echo $row['OrderID']; ?></td>
                <td><?php echo $row['ReceiptDate']; ?></td>
                <td><?php echo $row['ReceiptQuantity']; ?></td>
                <td>
                    <a href="?page=update_receipt.php&id=<?php echo $row['ReceiptID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_receipt.php&id=<?php echo $row['ReceiptID']; ?>" onclick="return confirm('Are you sure you want to delete this receipt?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>