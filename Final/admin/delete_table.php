<?php
$id = $_GET['id'];
$sql = "Delete from restaurant_table where TableID=" . $id;
$result = mysqli_query($conn, $sql);
header("Location: $urladmin?page=$table");