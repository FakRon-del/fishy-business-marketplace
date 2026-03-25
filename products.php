<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: auth/login.php");
    exit();
}

include("config/db.php");

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $result = $conn->query("
        SELECT products.*, shops.name AS shop_name
        FROM products
        JOIN shops ON products.shop_id = shops.id
        WHERE products.name LIKE '%$search%' 
        OR products.description LIKE '%$search%'
    ");
}else{
    $result = $conn->query("
        SELECT products.*, shops.name AS shop_name
        FROM products
        JOIN shops ON products.shop_id = shops.id
    ");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Fish</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #006994, #00b4d8, #90e0ef);
    margin: 0;
}

/* Navbar */
.navbar {
    background: rgba(255,255,255,0.9);
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
}

.nav-link {
    margin-left: 10px;
    text-decoration: none;
    padding: 6px 12px;
    background: #0096c7;
    color: white;
    border-radius: 20px;
}

/* Notification */
.message {
    background: #00b894;
    color: white;
    padding: 10px;
    text-align: center;
}

/* Container */
.container {
    padding: 20px;
}

/* Search */
.search-section {
    background: rgba(255,255,255,0.85);
    padding: 15px;
    border-radius: 30px;
    margin-bottom: 20px;
    text-align: center;
}

.search-form {
    display: flex;
    gap: 10px;
    max-width: 500px;
    margin: auto;
}

.search-input {
    flex: 1;
    padding: 10px;
    border-radius: 25px;
    border: 2px solid #90e0ef;
}

.search-btn {
    padding: 10px 20px;
    border-radius: 25px;
    border: none;
    background: #0096c7;
    color: white;
    cursor: pointer;
}

.search-btn:hover {
    transform: scale(1.05);
}

.search-stats {
    margin-top: 10px;
    color: #023e8a;
}

/* Products */
.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px,1fr));
    gap: 15px;
}

.product-card {
    background: white;
    border-radius: 15px;
    padding: 15px;
    text-align: center;
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
}

.price {
    background: #0096c7;
    color: white;
    padding: 8px;
    border-radius: 20px;
}

.btn {
    display: block;
    padding: 10px;
    background: #0096c7;
    color: white;
    border-radius: 20px;
    text-decoration: none;
    margin-top: 10px;
}

.btn.disabled {
    background: gray;
    pointer-events: none;
}
</style>
</head>

<body>

<!-- Navbar -->
<div class="navbar">
    <h2>🐟 Fishy Business</h2>
    <div>
        <a href="products.php" class="nav-link">Browse</a>
        <a href="cart/view_cart.php" class="nav-link">Cart</a>
        <a href="my_orders.php" class="nav-link">Orders</a>
        <a href="auth/logout.php" class="nav-link">Logout</a>
    </div>
</div>

<!-- Notification -->
<?php if(isset($_SESSION['message'])){ ?>
<div class="message">
    <?php 
    echo $_SESSION['message']; 
    unset($_SESSION['message']); 
    ?>
</div>
<?php } ?>

<div class="container">

<h1>Browse Fish</h1>

<!-- SEARCH UI -->
<div class="search-section">
<form method="GET" class="search-form">

<input 
type="text" 
name="search" 
class="search-input"
placeholder="🔍 Search for fish..."
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
>

<button type="submit" class="search-btn">Search</button>

</form>

<?php if(isset($_GET['search']) && $_GET['search'] != ""){ ?>
<div class="search-stats">
Found <?php echo $result->num_rows; ?> result(s)
</div>
<?php } ?>

</div>

<!-- PRODUCTS -->
<div class="products">

<?php if($result && $result->num_rows > 0){ ?>

<?php while($row = $result->fetch_assoc()){ ?>

<div class="product-card">

<img src="images/<?php echo $row['image'] ?: 'no-image.png'; ?>">

<h3><?php echo $row['name']; ?></h3>

<p><?php echo substr($row['description'],0,50); ?>...</p>

<p><strong>Shop:</strong> <?php echo $row['shop_name']; ?></p>

<p class="price">₱<?php echo number_format($row['price'],2); ?></p>

<?php if($row['stock'] > 0){ ?>

<p style="color:green;">Stock: <?php echo $row['stock']; ?></p>

<?php if($row['stock'] <= 5){ ?>
<p style="color:orange;">⚠️ Few left</p>
<?php } ?>

<a href="cart/add_to_cart.php?id=<?php echo $row['id']; ?>" class="btn">
Add to Cart
</a>

<?php } else { ?>

<p style="color:red;"><strong>Out of Stock</strong></p>

<button class="btn disabled">Unavailable</button>

<?php } ?>

</div>

<?php } ?>

<?php } else { ?>

<p>No products found</p>

<?php } ?>

</div>

</div>

</body>
</html>