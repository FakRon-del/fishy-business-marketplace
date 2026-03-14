<?php
session_start();
include("../config/db.php");

$order_id = $_GET['id'];

$order = $conn->query("
SELECT orders.*, users.email
FROM orders
JOIN users ON orders.user_id = users.id
WHERE orders.id=$order_id
")->fetch_assoc();
?>

<link rel="stylesheet" href="../css/style.css">

<h2>Order #<?php echo $order_id; ?></h2>

<p>User: <?php echo $order['email']; ?></p>

<p>Total: ₱<?php echo $order['total']; ?></p>

<p>Status: <?php echo $order['status']; ?></p>

<hr>

<table>

<tr>
<th>Image</th>
<th>Product</th>
<th>Shop</th>
<th>Quantity</th>
<th>Price</th>
</tr>

<?php

$items = $conn->query("
SELECT order_items.*, products.name, products.image, shops.name AS shop_name
FROM order_items
JOIN products ON order_items.product_id = products.id
JOIN shops ON products.shop_id = shops.id
WHERE order_items.order_id = $order_id
");

while($item = $items->fetch_assoc()){
?>

<tr>

<td>
<img src="../images/<?php echo $item['image']; ?>" width="70">
</td>

<td><?php echo $item['name']; ?></td>

<td><?php echo $item['shop_name']; ?></td>

<td><?php echo $item['quantity']; ?></td>

<td>₱<?php echo $item['price']; ?></td>

</tr>

<?php } ?>

</table>