<?php
$error = [];
if (isset($_POST['btnSubmit'])) {
    if (empty($_POST['receiptDate'])) {
        $error[] = "Please enter Receipt Date";
    }
    if (empty($_POST['receiptQuantity'])) {
        $error[] = "Please enter Receipt Quantity";
    }

    if (empty($error)) {
        $id = $_GET['id'];
        $receiptDate = $_POST['receiptDate'];
        $receiptQuantity = $_POST['receiptQuantity'];

        $stmt = $conn->prepare("SELECT * FROM receipt WHERE ReceiptID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $stmt = $conn->prepare("UPDATE receipt SET ReceiptDate = ?, ReceiptQuantity = ? WHERE ReceiptID = ?");
            $stmt->bind_param("sis", $receiptDate, $receiptQuantity, $id);
            $stmt->execute();

            header("Location: ?page=manage_receipt.php&msg=Update successful");
            exit();
        } else {
            $error[] = "Receipt not found";
        }
    }
} else {
    if (isset($_GET["id"])) {
        $receiptDate = "";
        $receiptQuantity = "";

        $stmt = $conn->prepare("SELECT * FROM receipt WHERE ReceiptID = ?");
        $stmt->bind_param("s", $_GET["id"]);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($row = $results->fetch_assoc()) {
            $orderID = htmlspecialchars($row["OrderID"]);
            $receiptDate = htmlspecialchars($row["ReceiptDate"]);
            $receiptQuantity = htmlspecialchars($row["ReceiptQuantity"]);
        }
    }
}
?>

<h2>Update Receipt Information</h2>
<hr style="background-color: red; height:2px" />
<ul style="color:red">
    <?php foreach ($error as $err) { echo "<li>$err</li>"; } ?>
</ul>
<form action="" method="POST">
    <div class="form-group">
        <label for="">Order ID</label>
        <input type="text" class="form-control" name="orderID" value="<?php echo isset($orderID) ? $orderID : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Receipt Date</label>
        <input type="date" class="form-control" name="receiptDate" value="<?php echo isset($receiptDate) ? $receiptDate : ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="">Receipt Quantity</label>
        <input type="number" class="form-control" name="receiptQuantity" value="<?php echo isset($receiptQuantity) ? $receiptQuantity : ''; ?>" required>
    </div>
    <button type="submit" name="btnSubmit" class="btn btn-primary">Update</button>
</form>
