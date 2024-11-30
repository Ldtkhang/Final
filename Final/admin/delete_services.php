<?php
$id = $_GET['id'];
$sql = "Delete from services where ServicesID=" . $id;
$result = mysqli_query($conn, $sql);
header("Location: $urladmin?page=$services");
