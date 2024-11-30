<?php
session_start();
if (!isset($_SESSION['UserRole']) || $_SESSION['UserRole'] !== 'Admin') {
    header("Location: /Final/index.php");
    exit();
}

$urladmin = "http://localhost:8080/Final/admin/";
$urluser = "http://localhost:8080/Final/";
$home = "home.php";
$area = "manage_area.php";
$dishanddrink = "manage_dishanddrink.php";
$table = "manage_table.php";
$order = "manage_orders.php";
$orderdetail = "manage_orderdetail.php";
$receipt = "manage_receipt.php";
$user = "manage_user.php";
$services = "manage_services.php";

$host = "localhost";
$username = "root";
$password = "";
$db = "final";
$conn = mysqli_connect($host, $username, $password, $db) or die("Can not connect database " . mysqli_connect_error());

include('theme.php');
