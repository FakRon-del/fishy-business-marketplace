<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['id'])){
header("Location: ../products.php");
exit();
}

$product_id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$product_id")->fetch_assoc();

if($product['stock'] <= 0){
    $_SESSION['message'] = "❌ Out of stock!";
    header("Location: ../products.php");
    exit();
}

# Get or create cart
$cart = $conn->query("SELECT * FROM carts WHERE user_id=$user_id")->fetch_assoc();

if(!$cart){
$conn->query("INSERT INTO carts (user_id) VALUES ($user_id)");
$cart_id = $conn->insert_id;
}else{
$cart_id = $cart['id'];
}

# Check if item exists
$item = $conn->query("
SELECT * FROM cart_items
WHERE cart_id=$cart_id AND product_id=$product_id
")->fetch_assoc();

if($item){

    if($item['quantity'] >= $product['stock']){
        $_SESSION['message'] = "⚠️ Maximum stock reached!";
        header("Location: ../products.php");
        exit();
    }

    $conn->query("
    UPDATE cart_items
    SET quantity = quantity + 1
    WHERE id=".$item['id']."
    ");


}else{
$conn->query("
INSERT INTO cart_items (cart_id,product_id,quantity)
VALUES ($cart_id,$product_id,1)
");
}

# ✅ Toast message
$_SESSION['message'] = "🐟 Added to cart successfully!";

header("Location: ../products.php");
exit();
?>