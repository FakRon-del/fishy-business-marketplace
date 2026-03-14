<?php
include("config/db.php");

$shop_id = $_GET['id'];

$shop = $conn->query("SELECT * FROM shops WHERE id=$shop_id")->fetch_assoc();

$result = $conn->query("
SELECT * FROM products
WHERE shop_id=$shop_id
");
?>

<h1><?php echo $shop['name']; ?> Store</h1>

<p><?php echo $shop['description']; ?></p>

<a href="products.php">← Back to Store</a>

<hr>

<?php
while($row = $result->fetch_assoc()){
?>

<div style="border:1px solid #ccc; padding:10px; margin:10px; width:200px; display:inline-block;">

<img src="images/<?php echo $row['image']; ?>" width="150">

<h3><?php echo $row['name']; ?></h3>

<p><?php echo $row['description']; ?></p>

<p>₱<?php echo $row['price']; ?></p>

<a href="cart/add_to_cart.php?id=<?php echo $row['id']; ?>">Add to Cart</a>

</div>

<?php
}
?>