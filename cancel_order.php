<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: auth/login.php");
exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

$conn->query("
UPDATE orders
SET status='cancelled'
WHERE id='$order_id' AND user_id='$user_id'
");

header("Location: my_orders.php");
exit();
?>