<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$shops = $conn->query("SELECT COUNT(*) as total FROM shops")->fetch_assoc();
$products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc();
$orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc();
$revenue = $conn->query("SELECT SUM(total) as total FROM orders")->fetch_assoc();
?>

<link rel="stylesheet" href="../css/style.css">

<h1>Admin Dashboard</h1>

<div class="dashboard">

<div class="stat-card">
<h2><?php echo $shops['total']; ?></h2>
<p>Total Shops</p>
</div>

<div class="stat-card">
<h2><?php echo $products['total']; ?></h2>
<p>Total Products</p>
</div>

<div class="stat-card">
<h2><?php echo $orders['total']; ?></h2>
<p>Total Orders</p>
</div>

<div class="stat-card">
<h2>₱<?php echo $revenue['total'] ?? 0; ?></h2>
<p>Total Revenue</p>
</div>

</div>

<hr>

<a href="add_shop.php">Create Shop</a><br><br>
<a href="view_shops.php">View Shops</a><br><br>
<a href="add_product.php">Add Product</a><br><br>
<a href="products.php">Manage Products</a><br><br>
<a href="view_orders.php">View Orders</a><br><br>
<a href="../products.php">View Store</a><br><br>
<a href="../auth/logout.php">Logout</a>