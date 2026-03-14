<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id = $_SESSION['user_id'];

if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
echo "Cart is empty";
exit();
}

$total = 0;

foreach($_SESSION['cart'] as $id => $qty){

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

$total += $product['price'] * $qty;

}

$conn->query("INSERT INTO orders (user_id,total) VALUES ('$user_id','$total')");

$order_id = $conn->insert_id;

foreach($_SESSION['cart'] as $id => $qty){

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

$conn->query("
INSERT INTO order_items (order_id,product_id,quantity,price)
VALUES ('$order_id','$id','$qty','".$product['price']."')
");

}

unset($_SESSION['cart']);
?>

<link rel="stylesheet" href="../css/style.css">

<div class="navbar">

<h2 class="logo">🐟 Fishy Business</h2>

<div class="nav-links">
<a href="../products.php">Browse Fish</a>
<a href="../my_orders.php">My Orders</a>
<a href="../auth/logout.php">Logout</a>
</div>

</div>


<div class="checkout-box">

<h1>🎉 Order Placed Successfully!</h1>

<p>Your order has been placed.</p>

<p><b>Order Number:</b> #<?php echo $order_id; ?></p>

<p><b>Total Paid:</b> ₱<?php echo $total; ?></p>

<br>

<a href="../products.php" class="btn">Continue Shopping</a>

<a href="../my_orders.php" class="btn">View My Orders</a>

</div>