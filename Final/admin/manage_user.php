<h3>User Management</h3>
<hr style="background-color: blue; height:2px">
<a class="btn btn-primary" href="?page=add_user.php">New User</a>
<hr />
<table id="example" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Gender</th>
            <th>Birth Date</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Query to select all users from the database
        $sql = "SELECT * FROM user";
        $result = mysqli_query($conn, $sql);

        // Fetch and display each user's details in the table
        while ($row = mysqli_fetch_array($result)) {
        ?>
            <tr>
                <td scope="row"><?php echo $row['UserID']; ?></td>
                <td><?php echo $row['UserFullName']; ?></td>
                <td><?php echo $row['UserPhone']; ?></td>
                <td><?php echo $row['UserEmail']; ?></td>
                <td><?php echo $row['UserAddress']; ?></td>
                <td><?php echo $row['UserGender']; ?></td>
                <td><?php echo $row['UserBirthDate']; ?></td>
                <td><?php echo $row['UserRole']; ?></td>
                <td>
                    <a href='?page=update_user.php&id=<?php echo $row['UserID']; ?>' class='glyphicon glyphicon-edit'></a> |
                    <a href='?page=delete_user.php&id=<?php echo $row['UserID']; ?>' onclick="return confirm('Are you sure?');" class="glyphicon glyphicon-remove"></a>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>