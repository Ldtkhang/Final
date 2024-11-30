<?php
$error = "";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['btnSubmit'])) {
    $dishanddrinkID = trim($_POST['dishanddrinkID']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);

    if (empty($dishanddrinkID)) {
        $error .= "<li>Please enter Dish and Drink ID</li>";
    }
    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $error .= "<li>Please enter a valid Quantity</li>";
    }
    if (empty($price) || !is_numeric($price) || $price < 0) {
        $error .= "<li>Please enter a valid Price</li>";
    }

    if ($error == "") {
        $id = $_GET['id']; 
        $dishanddrinkID = $_GET['dishanddrinkID'];

        $stmt = $conn->prepare("SELECT * FROM orderdetail WHERE OrderID = ? AND DishanddrinkID = ?");
        $stmt->bind_param("ss", $id, $dishanddrinkID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $stmt = $conn->prepare("UPDATE orderdetail SET DishanddrinkID = ?, OrderdetailQuantity = ?, OrderdetailPrice = ? WHERE OrderID = ? AND DishanddrinkID = ?");
            $stmt->bind_param("sssss", $dishanddrinkID, $quantity, $price, $id, $dishanddrinkID);

            if ($stmt->execute()) {
                echo '<script>alert("Update successful")</script>';
                echo '<meta http-equiv="refresh" content="0;URL=?page=manage_orderdetail.php"/>';
            } else {
                $error .= "<li>Error updating record: " . $conn->error . "</li>";
            }
        } else {
            $error .= "<li>Order detail not found</li>";
        }

        $stmt->close();
    }
} else {
    if (isset($_GET["id"]) && isset($_GET["dishanddrinkID"])) {
        $stmt = $conn->prepare("SELECT * FROM orderdetail WHERE OrderID = ? AND DishanddrinkID = ?");
        $stmt->bind_param("ss", $_GET["id"], $_GET["dishanddrinkID"]);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $orderID = htmlspecialchars($row["OrderID"]);
            $dishanddrinkID = htmlspecialchars($row["DishanddrinkID"]);
            $servicesID = htmlspecialchars($row["ServicesID"]);
            $quantity = htmlspecialchars($row["OrderdetailQuantity"]);
            $price = htmlspecialchars($row["OrderdetailPrice"]);
        } else {
            $error .= "<li>Order detail not found</li>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<h2>Update Order Detail Information</h2>
<hr style="background-color: red; height:2px" />
<ul style="color:red">
    <?php echo $error; ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Order ID</label>
        <input type="text" class="form-control" name="orderID" value="<?php echo isset($orderID) ? $orderID : htmlspecialchars($_GET['id']); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Dish and Drink ID</label>
        <input type="text" class="form-control" name="dishanddrinkID" value="<?php echo isset($dishanddrinkID) ? $dishanddrinkID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Services ID</label>
        <input type="text" class="form-control" name="servicesID" value="<?php echo isset($servicesID) ? $servicesID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Quantity</label>
        <input type="number" class="form-control" name="quantity" value="<?php echo isset($quantity) ? $quantity : ''; ?>">
    </div>
    <div class="form-group">
        <label for="">Price</label>
        <input type="text" class="form-control" name="price" value="<?php echo isset($price) ? $price : ''; ?>">
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>