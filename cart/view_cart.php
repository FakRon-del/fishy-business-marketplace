<?php
session_start();
include("../config/db.php");
?>

<link rel="stylesheet" href="../css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<div class="navbar">

<h2 class="logo">🐟 Fishy Business</h2>

<div class="nav-links">

<a href="../products.php">Browse Fish</a>
<a href="../my_orders.php">My Orders</a>
<a href="../auth/logout.php">Logout</a>

</div>

</div>

<h1>Your Cart</h1>

<?php

if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){

echo "<p>Your cart is empty.</p>";
echo "<a href='../products.php' class='btn'>Browse Fish</a>";
exit();

}

$total = 0;

?>

<table border="1" style="margin:auto; background:white; padding:20px;">

<tr>

<th>Image</th>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Total</th>
<th>Action</th>

</tr>

<?php

foreach($_SESSION['cart'] as $id => $qty){

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

$subtotal = $product['price'] * $qty;

$total += $subtotal;

?>

<tr>

<td>
<img src="../images/<?php echo $product['image']; ?>" width="80">
</td>

<td><?php echo $product['name']; ?></td>

<td>₱<?php echo $product['price']; ?></td>

<td><?php echo $qty; ?></td>

<td>₱<?php echo $subtotal; ?></td>

<td>
<a href="remove_from_cart.php?id=<?php echo $id; ?>">Remove</a>
</td>

</tr>

<?php } ?>

</table>

<h2>Total: ₱<?php echo $total; ?></h2>

<br>

<a href="checkout.php">
<button class="btn">Checkout</button>
</a>

<br><br>

<a href="../products.php">Continue Shopping</a>