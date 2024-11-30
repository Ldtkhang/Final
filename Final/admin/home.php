<h2>AREA LIST</h2>
<hr style="background-color: red;">
<table class="table">
    <thead>
        <tr>
            <th>Area ID</th>
            <th>Area Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM area";
        $result = mysqli_query($conn, $sql);
        if ($result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            while ($row = mysqli_fetch_array($result)) {
        ?>
                <tr>
                    <td><?php echo $row['AreaID']; ?></td>
                    <td><?php echo $row['AreaName']; ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<h2>SERVICES LIST</h2>
<hr style="background-color: red;">
<table class="table">
    <thead>
        <tr>
            <th>Service ID</th>
            <th>Service Name</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM services";
        $result = mysqli_query($conn, $sql);
        if ($result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            while ($row = mysqli_fetch_array($result)) {
        ?>
                <tr>
                    <td><?php echo $row['ServicesID']; ?></td>
                    <td><?php echo $row['ServiceName']; ?></td>
                    <td><?php echo $row['ServicesPrice']; ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<h2>ORDER LIST</h2>
<hr style="background-color: red;">
<table class="table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Area ID</th>
            <th>Table ID</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM orders WHERE OrdersStatus = 'Processing'";
        $result = mysqli_query($conn, $sql);
        if ($result === false) {
            echo "Error: " . mysqli_error($conn);
        } else {
            while ($row = mysqli_fetch_array($result)) {
        ?>
                <tr>
                    <td><?php echo $row['OrderID']; ?></td>
                    <td><?php echo $row['UserID']; ?></td>
                    <td><?php echo $row['OrdersDate']; ?></td>
                    <td><?php echo $row['OrdersStatus']; ?></td>
                    <td><?php echo $row['AreaID']; ?></td>
                    <td><?php echo $row['TableID']; ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
