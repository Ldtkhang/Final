<h3>Dish and Drink Management</h3>
<hr style="background-color: blue; height:2px">
<a class="btn btn-primary" href="?page=add_dishanddrink.php">New Dish/Drink</a>
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Type</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM dishanddrink";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['DishanddrinkID']; ?></td>
                <td><?php echo $row['DishanddrinkName']; ?></td>
                <td><?php echo $row['DishanddrinkPrice']; ?></td>
                <td><?php echo $row['DishanddrinkType']; ?></td>
                <td><img src="<?php echo '../admin/pimgs/' . $row['DishanddrinkImage']; ?>" style="width:150px" /></td>
                <td>
                    <a href="?page=update_dishanddrink.php&id=<?php echo $row['DishanddrinkID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_dishanddrink.php&id=<?php echo $row['DishanddrinkID']; ?>" onclick="return confirm('Are you sure you want to delete this dish/drink?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
