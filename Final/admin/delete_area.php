<?php
$id = $_GET['id'];

mysqli_begin_transaction($conn);

try {
    $sqlDeleteTables = "DELETE FROM restaurant_table WHERE AreaID = ?";
    $stmt = mysqli_prepare($conn, $sqlDeleteTables);
    mysqli_stmt_bind_param($stmt, 's', $id); // Giả sử AreaID là kiểu chuỗi
    mysqli_stmt_execute($stmt);
    
    $sqlDeleteArea = "DELETE FROM area WHERE AreaID = ?";
    $stmt = mysqli_prepare($conn, $sqlDeleteArea);
    mysqli_stmt_bind_param($stmt, 's', $id); // Giả sử AreaID là kiểu chuỗi
    mysqli_stmt_execute($stmt);

    // Cam kết giao dịch
    mysqli_commit($conn);
    header("Location: $urladmin?page=manage_area.php");
    exit();
} catch (Exception $e) {
    // Nếu có lỗi xảy ra, hủy giao dịch
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
}
?>
