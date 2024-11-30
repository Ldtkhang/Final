<?php
///////////////////////////////////// Check connection //////////////////////////
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<h3>Area Management</h3>
<hr style="background-color: blue; height:2px">
<a class="btn btn-primary" href="?page=add_area.php">New Area</a>
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM area";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['AreaID']; ?></td>
                <td><?php echo $row['AreaName']; ?></td>
                <td>
                    <a href="?page=update_area.php&id=<?php echo $row['AreaID']; ?>" class="glyphicon glyphicon-edit"></a> |
                    <a href="?page=delete_area.php&id=<?php echo $row['AreaID']; ?>" onclick="return confirm('Are you sure you want to delete this area?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
