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

<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<div style="background:white;padding:10px;margin-bottom:10px">

<a href="products.php">Browse Fish</a> |
<a href="my_orders.php">My Orders</a> |
<a href="cart/view_cart.php">Cart</a> |
<a href="auth/logout.php">Logout</a>

</div>

<form method="GET">

<input type="text" name="search" placeholder="Search fish...">

<button type="submit">Search</button>

</form>

<br>
<div class="products">

<?php
while($row = $result->fetch_assoc()){
?>

<div class="product-card">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p class="shop">
Shop:
<a href="shop.php?id=<?php echo $row['shop_id']; ?>">
<?php echo $row['shop_name']; ?>
</a>
</p>

<p><?php echo $row['description']; ?></p>

<p class="price">₱<?php echo $row['price']; ?></p>

<a href="cart/add_to_cart.php?id=<?php echo $row['id']; ?>">
<button class="btn">Add to Cart</button>
</a>

</div>

<?php
}
?>

</div>