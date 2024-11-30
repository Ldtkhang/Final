<?php

$id = $_GET['id'];

$sql = "Delete from dishanddrink where DishanddrinkID=" . $id;
$result = mysqli_query($conn, $sql);
header("Location: $urladmin?page=$dishanddrink");