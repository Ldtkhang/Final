<?php
if (isset($_GET['id']) && isset($_POST['quantity'])) {
    $dishanddrinkID = intval($_GET['id']);
    $quantity = intval($_POST['quantity']); // User-defined quantity
    $servicesID = isset($_POST['services']) && !empty($_POST['services']) ? intval($_POST['services']) : null; // Allow no service

    // Validate quantity
    if ($quantity < 1) {
        $quantity = 1; // Ensure minimum quantity of 1
    }

    $userID = $_SESSION['usid']; // User ID from session

    // Check if the item already exists in the cart
    $check_sql = "SELECT Quantity, ServicesID FROM cart WHERE UserID = ? AND DishanddrinkID = ?";
    if ($stmt = mysqli_prepare($conn, $check_sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $userID, $dishanddrinkID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existingQuantity, $existingServicesID);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if ($existingQuantity) {
            // Item exists in the cart
            if (is_null($existingServicesID)) {
                // Current entry has no service
                // If a service is being added now
                if (!is_null($servicesID)) {
                    // Update the existing row to include the service
                    $update_sql = "UPDATE cart SET Quantity = ?, ServicesID = ? WHERE UserID = ? AND DishanddrinkID = ?";
                    if ($stmt = mysqli_prepare($conn, $update_sql)) {
                        $newQuantity = $existingQuantity + $quantity; // Increase quantity
                        mysqli_stmt_bind_param($stmt, "iiii", $newQuantity, $servicesID, $userID, $dishanddrinkID);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    // No service, just update the quantity
                    $newQuantity = $existingQuantity + $quantity; // Increase quantity
                    $update_sql = "UPDATE cart SET Quantity = ? WHERE UserID = ? AND DishanddrinkID = ?";
                    if ($stmt = mysqli_prepare($conn, $update_sql)) {
                        mysqli_stmt_bind_param($stmt, "iii", $newQuantity, $userID, $dishanddrinkID);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
            } else {
                // Current entry has a service
                if (!is_null($servicesID)) {
                    // If user tries to add the same product with a service, show error
                    if ($existingServicesID != $servicesID) {
                        echo '<script>alert("Error: This product already has a service associated with it. Cannot add a different service.");</script>';
                        echo '<meta http-equiv="refresh" content="0;URL=?page=shoppingcart.php"/>';
                        exit;
                    } else {
                        // Same service, just increase the quantity
                        $newQuantity = $existingQuantity + $quantity; // Increase quantity
                        $update_sql = "UPDATE cart SET Quantity = ? WHERE UserID = ? AND DishanddrinkID = ? AND ServicesID = ?";
                        if ($stmt = mysqli_prepare($conn, $update_sql)) {
                            mysqli_stmt_bind_param($stmt, "iiii", $newQuantity, $userID, $dishanddrinkID, $existingServicesID);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }
                    }
                } else {
                    // No service, just increase the quantity of the existing entry
                    $newQuantity = $existingQuantity + $quantity; // Increase quantity
                    $update_sql = "UPDATE cart SET Quantity = ? WHERE UserID = ? AND DishanddrinkID = ? AND ServicesID = ?";
                    if ($stmt = mysqli_prepare($conn, $update_sql)) {
                        mysqli_stmt_bind_param($stmt, "iiii", $newQuantity, $userID, $dishanddrinkID, $existingServicesID);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                }
            }
        } else {
            // Item does not exist in the cart, insert it
            $insert_sql = "INSERT INTO cart (UserID, DishanddrinkID, ServicesID, Quantity) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $insert_sql)) {
                mysqli_stmt_bind_param($stmt, "iiii", $userID, $dishanddrinkID, $servicesID, $quantity);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    echo '<script>alert("Item added to cart successfully.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
} else {
    echo '<script>alert("Invalid request.");</script>';
    echo '<meta http-equiv="refresh" content="0;URL=?page=home.php"/>';
}
?>
