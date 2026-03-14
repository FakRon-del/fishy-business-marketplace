<?php
include("config/db.php");

$id = $_GET['id'];

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

echo "<h2>".$product['name']."</h2>";
echo "<p>".$product['description']."</p>";
echo "<p>".$product['price']."</p>";

echo "<a href='cart/add_to_cart.php?id=".$product['id']."'>Add to Cart</a>";
?>