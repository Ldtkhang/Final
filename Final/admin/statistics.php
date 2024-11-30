<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="../asset/css/style1.css">
</head>

<body>

<h2>Statistics</h2>
<hr style="background-color: red;">

<h3>Statistics Overview</h3>
<hr style="background-color: blue; height:2px">
<table id="statistics" class="display dt-responsive nowrap" style="width:100%; background-color: white;">
    <thead>
        <tr>
            <th>Statistic</th>
            <th>Count</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Areas</td>
            <td>
                <?php
                $sql = "SELECT COUNT(*) AS total_areas FROM area";
                $result = mysqli_query($conn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    echo $row['total_areas'];
                }
                ?>
            </td>
        </tr>
        
        <tr>
            <td>Total Dishes and Drinks</td>
            <td>
                <?php
                $sql = "SELECT COUNT(*) AS total_dishes FROM dishanddrink";
                $result = mysqli_query($conn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    echo $row['total_dishes'];
                }
                ?>
            </td>
        </tr>
        
        <tr>
            <td>Total Orders</td>
            <td>
                <?php
                $sql = "SELECT COUNT(*) AS total_orders FROM orders";
                $result = mysqli_query($conn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
                    echo $row['total_orders'];
                }
                ?>
            </td>
        </tr>

        <tr>
            <td>Order Status Breakdown</td>
            <td>
                <?php
                $sql = "SELECT OrdersStatus, COUNT(*) AS status_count FROM orders GROUP BY OrdersStatus";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo $row['OrdersStatus'] . ": " . $row['status_count'] . "<br>";
                }
                ?>
            </td>
        </tr>
    </tbody>
</table>

<h3>Total Revenue</h3>
<?php
$sql = "SELECT SUM(OrderdetailPrice * OrderdetailQuantity) AS total_revenue FROM orderdetail";
$result = mysqli_query($conn, $sql);
if ($row = mysqli_fetch_assoc($result)) {
    echo "<p>Total Revenue: $" . number_format($row['total_revenue'], 2) . "</p>";
}
?>

<h3>Most Ordered Dishes</h3>
<canvas id="dishesChart" width="400" height="200"></canvas>
<script>
    var ctx = document.getElementById('dishesChart').getContext('2d');
    var dishesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total Quantity Ordered',
                data: [],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
$sql = "
    SELECT 
        d.DishanddrinkName, 
        SUM(od.OrderdetailQuantity) AS TotalQuantityOrdered
    FROM orderdetail od
    JOIN dishanddrink d ON od.DishanddrinkID = d.DishanddrinkID
    WHERE d.DishanddrinkType = 'Dish' 
    GROUP BY d.DishanddrinkID
    ORDER BY TotalQuantityOrdered DESC
";

$result = mysqli_query($conn, $sql);
if ($result === false) {
    echo "Error: " . mysqli_error($conn);
} else {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo "<script>
                dishesChart.data.labels.push('" . addslashes($row['DishanddrinkName']) . "');
                dishesChart.data.datasets[0].data.push(" . $row['TotalQuantityOrdered'] . ");
                dishesChart.update();
            </script>";
        }
    }
}
?>

<h3>Most Ordered Drinks</h3>
<canvas id="drinksChart" width="400" height="200"></canvas>
<script>
    var ctxDrinks = document.getElementById('drinksChart').getContext('2d');
    var drinksChart = new Chart(ctxDrinks, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total Quantity Ordered',
                data: [],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php
$sql = "
    SELECT 
        d.DishanddrinkName, 
        SUM(od.OrderdetailQuantity) AS TotalQuantityOrdered
    FROM orderdetail od
    JOIN dishanddrink d ON od.DishanddrinkID = d.DishanddrinkID
    WHERE d.DishanddrinkType = 'Drink' 
    GROUP BY d.DishanddrinkID
    ORDER BY TotalQuantityOrdered DESC
";

$result = mysqli_query($conn, $sql);
if ($result === false) {
    echo "Error: " . mysqli_error($conn);
} else {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            echo "<script>
                drinksChart.data.labels.push('" . addslashes($row['DishanddrinkName']) . "');
                drinksChart.data.datasets[0].data.push(" . $row['TotalQuantityOrdered'] . ");
                drinksChart.update();
            </script>";
        }
    }
}
?>
</body>
