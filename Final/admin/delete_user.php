<?php

$id = $_GET['id'];

$sql = "Delete from user where UserID=" . $id;
$result = mysqli_query($conn, $sql);
header("Location: $urladmin?page=$user");