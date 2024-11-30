<?php
if (isset($_GET['id'])) {
    $cartID = intval($_GET['id']);

    $delete_sql = "DELETE FROM cart WHERE CartID = ?";
    if ($stmt = mysqli_prepare($conn, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $cartID);
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Item removed from cart.");</script>';
        } else {
            echo "Error removing item: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
    echo '<meta http-equiv="refresh" content="0;URL=?page=shoppingcart.php"/>';
} else {
    echo '<script>alert("Invalid request.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
}
?>
