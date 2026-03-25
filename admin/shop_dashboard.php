<?php
session_start();
include("../config/db.php");

# 🔒 Restrict access
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'shop_admin'){
    header("Location: ../index.php");
    exit();
}

$shop_id = $_SESSION['shop_id'];

$shop = $conn->query("SELECT * FROM shops WHERE id=$shop_id")->fetch_assoc();


# Get products of this shop
$products = $conn->query("
SELECT * FROM products
WHERE shop_id = $shop_id
");

# Get orders for this shop
$orders = $conn->query("
SELECT DISTINCT orders.id, orders.total, orders.status
FROM orders
JOIN order_items ON orders.id = order_items.order_id
JOIN products ON order_items.product_id = products.id
WHERE products.shop_id = $shop_id
ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Shop Dashboard</title>
<h2>🏪 My Shop: <?php echo $shop['name']; ?></h2>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #006994, #00b4d8);
    color: #023e8a;
    padding: 20px;
}

/* Header */
.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:white;
    padding:15px 25px;
    border-radius:15px;
    margin-bottom:20px;
}

.header h2 {
    margin:0;
}

/* Cards */
.card {
    background:white;
    padding:15px;
    margin:10px;
    border-radius:15px;
    display:inline-block;
    width:220px;
    text-align:center;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.card img {
    width:150px;
    border-radius:10px;
}

/* Buttons */
.btn {
    display:inline-block;
    padding:8px 15px;
    margin-top:10px;
    background:#0096c7;
    color:white;
    text-decoration:none;
    border-radius:20px;
}

.btn:hover {
    background:#0077be;
}

/* Section */
.section {
    margin-top:30px;
}

.order-box {
    background:white;
    padding:15px;
    margin:10px 0;
    border-radius:15px;
}
</style>

</head>

<body>

<div class="header">
    <h2>🐟 My Shop Dashboard</h2>
    <a href="../auth/logout.php" class="btn">Logout</a>
</div>

<!-- PRODUCTS -->
<div class="section">
<h3>📦 My Products</h3>

<a href="add_product.php" class="btn">+ Add Product</a>

<br><br>

<?php while($p = $products->fetch_assoc()){ ?>

<div class="card">

<img src="../images/<?php echo $p['image']; ?>">

<h4><?php echo $p['name']; ?></h4>

<p>₱<?php echo $p['price']; ?></p>

<p>Stock: <?php echo $p['stock']; ?></p>

<a href="edit_product.php?id=<?php echo $p['id']; ?>" class="btn">Edit</a>

</div>

<?php } ?>

</div>

<!-- ORDERS -->
<div class="section">
<h3>🧾 Orders from Your Shop</h3>

<?php while($o = $orders->fetch_assoc()){ ?>

<div class="order-box">

<strong>Order #<?php echo $o['id']; ?></strong><br>
Total: ₱<?php echo $o['total']; ?><br>
Status: <?php echo $o['status']; ?>

</div>

<?php } ?>

</div>

</body>
</html>