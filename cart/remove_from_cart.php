<?php
include("../config/db.php");

$id = $_GET['id'];

$conn->query("DELETE FROM cart_items WHERE id=$id");

header("Location: view_cart.php");
?>