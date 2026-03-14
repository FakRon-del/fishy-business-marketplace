<?php

session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];

if(!isset($_SESSION['user_id'])){
header("Location: auth/login.php");
exit();
}

$result = $conn->query("
SELECT * FROM orders
WHERE user_id = $user_id
ORDER BY id DESC
");
?>

<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<h1>My Orders</h1>

<a href="products.php">← Back to Store</a>

<hr>

<?php
while($order = $result->fetch_assoc()){
?>

<h3>Order #<?php echo $order['id']; ?></h3>

<p>Total: ₱<?php echo $order['total']; ?></p>

<p>Status: 
<b style="color:
<?php echo $order['status']=='cancelled' ? 'red' : 'green'; ?>">
<?php echo $order['status']; ?>
</b>
</p>

<?php if($order['status'] == 'placed'){ ?>

<a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="btn">
Cancel Order
</a>

<?php } ?>

<table border="1">

<tr>
<th>Image</th>
<th>Product</th>
<th>Shop</th>
<th>Quantity</th>
<th>Price</th>
</tr>

<?php

$order_id = $order['id'];

$items = $conn->query("
SELECT order_items.*, products.name, products.image, shops.name AS shop_name
FROM order_items
JOIN products ON order_items.product_id = products.id
JOIN shops ON products.shop_id = shops.id
WHERE order_items.order_id = $order_id
");

while($item = $items->fetch_assoc()){

$image = $item['image'];

if($image == "" || !file_exists("images/".$image)){
$image = "no-image.png";
}
?>

<tr>

<td>
<img src="images/<?php echo $image; ?>" width="80">
</td>

<td><?php echo $item['name']; ?></td>

<td><?php echo $item['shop_name']; ?></td>

<td><?php echo $item['quantity']; ?></td>

<td>₱<?php echo $item['price']; ?></td>

</tr>

<?php
}
?>

</table>

<hr>

<?php
}
?>