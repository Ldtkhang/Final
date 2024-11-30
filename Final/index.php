<?php
session_start();
$urladmin = "http://localhost:8080/Final/admin/";
$urluser = "http://localhost:8080/Final/";
$home = "home.php";
$order = "order.php";
$shoppingcart = "shoppingcart.php";
$register = "register.php";
$logout = "logout.php";

$host = "localhost";
$username = "root";
$password = "";
$db = "final";
$conn = mysqli_connect($host, $username, $password, $db) or die("Can not connect database " . mysqli_connect_error());
include('theme.php');
