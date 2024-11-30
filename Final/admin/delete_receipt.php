<?php
$id = $_GET['id'];
$sql = "Delete from receipt where ReceiptID=" .$id;
$result = mysqli_query($conn, $sql);
header("Location: $urladmin?page=$receipt");