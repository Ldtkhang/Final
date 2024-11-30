<?php
if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $cartID = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);

    // Ensure quantity is at least 1
    if ($quantity < 1) {
        $quantity = 1;
    }

    $update_sql = "UPDATE cart SET Quantity = ? WHERE CartID = ?";
    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $quantity, $cartID);
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Cart updated successfully.");</script>';
        } else {
            echo "Error updating cart: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
    echo '<meta http-equiv="refresh" content="0;URL=?page=shoppingcart.php"/>';
} else {
    echo '<script>alert("Invalid request.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
}
?>
