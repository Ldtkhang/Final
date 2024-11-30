<h3>Services Management</h3>
<hr style="background-color: blue; height:2px">
<a class="btn btn-primary" href="?page=add_services.php">New Service</a>
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM services";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['ServicesID']; ?></td>
                <td><?php echo $row['ServiceName']; ?></td>
                <td><?php echo $row['ServicesPrice']; ?></td>
                <td>
                    <a href="?page=update_services.php&id=<?php echo $row['ServicesID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_services.php&id=<?php echo $row['ServicesID']; ?>" onclick="return confirm('Are you sure you want to delete this service?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>