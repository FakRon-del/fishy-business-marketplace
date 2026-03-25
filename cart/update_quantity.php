<?php
include("../config/db.php");

$id = $_GET['id'];
$action = $_GET['action'];

if($action == "increase"){
$conn->query("UPDATE cart_items SET quantity = quantity + 1 WHERE id=$id");
}

if($action == "decrease"){
$conn->query("UPDATE cart_items SET quantity = quantity - 1 WHERE id=$id");

# Remove if 0
$conn->query("DELETE FROM cart_items WHERE id=$id AND quantity <= 0");
}

header("Location: view_cart.php");
?>