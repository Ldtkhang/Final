<?php
$error = "";
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['usid']) || $_SESSION['usid'] == "") {
    echo '<script>alert("PLEASE LOGIN")</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=login.php"/>';
    exit;
}

if (isset($_POST['btnSubmit'])) {
    // Nếu chưa chọn Area hoặc Table, hiển thị lỗi
    if ($_POST['area'] == "" || $_POST['table'] == "") {
        $error .= "<li>Please select both Area and Table</li>";
    }

    if ($error == "") {
        $us_id = $_SESSION['usid'];
        $area_id = $_POST['area'];
        $table_id = $_POST['table'];

        $orders_date = date('Y-m-d H:i:s');

        // Kiểm tra người dùng có tồn tại không
        $sql = "SELECT * FROM user WHERE UserID='" . $us_id . "'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Thêm đơn hàng vào cơ sở dữ liệu với trạng thái "Pending"
            $sql = "INSERT INTO orders (UserID, OrdersDate, OrdersStatus, AreaID, TableID) 
                    VALUES('$us_id', '$orders_date', 'Pending', '$area_id', '$table_id')";
            mysqli_query($conn, $sql);

            $order_id = mysqli_insert_id($conn);

            // Thêm bản ghi vào bảng receipt
            $receipt_date = date('Y-m-d');
            $receipt_quantity = 1;
            $sql = "INSERT INTO receipt (OrderID, ReceiptDate, ReceiptQuantity) 
                    VALUES ('$order_id', '$receipt_date', '$receipt_quantity')";
            mysqli_query($conn, $sql);

            // Cập nhật trạng thái của bàn thành "Unavailable"
            $sql = "UPDATE restaurant_table SET TableStatus = 'Unavailable' WHERE TableID = '$table_id'";
            mysqli_query($conn, $sql);

            // Lưu thông tin area_id và table_id vào session
            $_SESSION['area_id'] = $area_id;
            $_SESSION['table_id'] = $table_id;

            echo '<script>alert("ADD ORDER SUCCESSFUL")</script>';
            echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
        }
    }
}

// Truy vấn để lấy danh sách khu vực
$area_sql = "SELECT * FROM area";
$area_result = mysqli_query($conn, $area_sql);

$table_options = "";

// Truy vấn để lấy danh sách bàn dựa trên khu vực đã chọn
if (isset($_POST['area'])) {
    $selected_area_id = $_POST['area'];

    $table_sql = "SELECT TableID, TableName, TableStatus FROM restaurant_table WHERE AreaID = '$selected_area_id'";
    $table_result = mysqli_query($conn, $table_sql);

    while ($table_row = mysqli_fetch_assoc($table_result)) {
        if ($table_row['TableStatus'] === 'Available') {
            $table_options .= '<option value="' . $table_row['TableID'] . '">' . $table_row['TableName'] . ' (' . $table_row['TableStatus'] . ')</option>';
        } else {
            $table_options .= '<option value="' . $table_row['TableID'] . '" disabled>' . $table_row['TableName'] . ' (' . $table_row['TableStatus'] . ')</option>';
        }
    }
}
?>

<h2>Add Order</h2>
<hr style="background-color: blue; height:3px" />

<ul style="color: red;">
    <?php echo $error; ?>
</ul>

<form action="" method="post">
    <div class="form-group">
        <label for="">Select Area</label>
        <select name="area" class="form-control" id="areaSelect" onchange="this.form.submit()" required>
            <option value="">-- Choose Area --</option>
            <?php while ($area_row = mysqli_fetch_assoc($area_result)) { ?>
                <option value="<?php echo $area_row['AreaID']; ?>" <?php echo (isset($selected_area_id) && $selected_area_id == $area_row['AreaID']) ? 'selected' : ''; ?>>
                    <?php echo $area_row['AreaName']; ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="">Select Table</label>
        <select name="table" class="form-control" required>
            <option value="">-- Choose Table --</option>
            <?php echo $table_options; ?>
        </select>
    </div>
    <input type="hidden" name="orders_date" value="<?php echo date('Y-m-d H:i:s'); ?>">
    <button type="submit" name="btnSubmit" class="btn btn-primary">Submit</button>
    <hr />
</form>
