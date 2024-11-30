<h3>Table Management</h3>
<hr style="background-color: blue; height:2px">

<a class="btn btn-primary" href="?page=add_table.php">New Table</a>

<form method="post" style="display:inline;">
    <button type="submit" name="reset_status" class="btn btn-warning">Reset All Tables to Available</button>
</form>

<hr />

<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Table Name</th>
            <th>Area ID</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM restaurant_table";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['TableID']; ?></td>
                <td><?php echo $row['TableName']; ?></td>
                <td><?php echo $row['AreaID']; ?></td>
                <td><?php echo $row['TableStatus']; ?></td>
                <td>
                    <a href="?page=update_table.php&id=<?php echo $row['TableID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_table.php&id=<?php echo $row['TableID']; ?>" onclick="return confirm('Are you sure you want to delete this table?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_status'])) {
    $updateStatusSql = "UPDATE restaurant_table SET TableStatus = 'Available' WHERE TableStatus = 'Unavailable'";
    if (mysqli_query($conn, $updateStatusSql)) {
        echo '<script>alert("All tables have been reset to Available.");</script>';
        echo '<meta http-equiv="refresh" content="0;URL=?page=manage_table.php"/>';
    } else {
        echo '<script>alert("Failed to reset table statuses.");</script>';
    }
}
?>
