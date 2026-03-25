<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

# Get cart
$cart = $conn->query("SELECT * FROM carts WHERE user_id=$user_id")->fetch_assoc();

if(!$cart){
    die("No cart found");
}

$cart_id = $cart['id'];

# Get cart items WITH STOCK
$items = $conn->query("
SELECT cart_items.*, products.price, products.stock, products.name
FROM cart_items
JOIN products ON cart_items.product_id = products.id
WHERE cart_items.cart_id = $cart_id
");

$total = 0;
$hasItems = false;

# ✅ FIRST PASS: CHECK STOCK
while($row = $items->fetch_assoc()){
    $hasItems = true;

    if($row['stock'] < $row['quantity']){
        die("❌ Not enough stock for: " . $row['name']);
    }

    $total += $row['price'] * $row['quantity'];
}

if(!$hasItems){
    die("Cart is empty");
}

# ✅ CREATE ORDER
$conn->query("
INSERT INTO orders (user_id,total,status)
VALUES ($user_id,$total,'placed')
");

$order_id = $conn->insert_id;

# 🔁 RE-GET ITEMS (important after fetch loop)
$items = $conn->query("
SELECT cart_items.*, products.price, products.stock
FROM cart_items
JOIN products ON cart_items.product_id = products.id
WHERE cart_items.cart_id = $cart_id
");

# ✅ SECOND PASS: INSERT + DEDUCT STOCK
while($row = $items->fetch_assoc()){

    $product_id = $row['product_id'];
    $qty = $row['quantity'];
    $price = $row['price'];

    # Insert order item
    $conn->query("
    INSERT INTO order_items (order_id,product_id,quantity,price)
    VALUES ($order_id,$product_id,$qty,$price)
    ");

    # 🔥 DEDUCT STOCK
    $conn->query("
    UPDATE products 
    SET stock = stock - $qty 
    WHERE id = $product_id
    ");
}

# ✅ CLEAR CART
$conn->query("DELETE FROM cart_items WHERE cart_id=$cart_id");

$_SESSION['message'] = "✅ Order placed successfully!";

header("Location: ../my_orders.php");
exit();
?>