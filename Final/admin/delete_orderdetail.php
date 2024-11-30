<?php
// Lấy giá trị OrderID và DishanddrinkID từ GET
$orderID = $_GET['id']; // OrderID
$dishanddrinkID = $_GET['dishanddrinkID']; // DishanddrinkID

// Kiểm tra xem giá trị có hợp lệ không
if (isset($orderID) && isset($dishanddrinkID)) {
    // Truy vấn SQL để xóa bản ghi trong bảng orderdetail
    $sql = "DELETE FROM orderdetail WHERE OrderID = $orderID AND DishanddrinkID = $dishanddrinkID";
    
    // Thực thi truy vấn
    if (mysqli_query($conn, $sql)) {
        // Nếu xóa thành công, chuyển hướng về trang quản lý chi tiết đơn hàng
        header("Location: $urladmin?page=manage_orderdetail.php&status=success");
        exit(); // Dừng thực thi tiếp theo
    } else {
        // Nếu có lỗi xảy ra, có thể chuyển hướng đến trang khác hoặc hiển thị thông báo lỗi
        header("Location: $urladmin?page=manage_orderdetail.php&status=error");
        exit();
    }
} else {
    // Nếu giá trị không hợp lệ, chuyển hướng về trang quản lý chi tiết đơn hàng
    header("Location: $urladmin?page=manage_orderdetail.php&status=invalid");
    exit();
}