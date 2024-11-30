<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'home.php';
include($page);
?>