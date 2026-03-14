<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$result = $conn->query("
SELECT orders.*, users.email 
FROM orders
JOIN users ON orders.user_id = users.id
ORDER BY orders.id DESC
");
?>

<link rel="stylesheet" href="../css/style.css">

<div class="navbar">

<h2 class="logo">🐟 Fishy Business Admin</h2>

<div class="nav-links">
<a href="dashboard.php">Dashboard</a>
<a href="../products.php">View Store</a>
<a href="../auth/logout.php">Logout</a>
</div>

</div>

<h1 style="text-align:center;">All Orders</h1>

<table>

<tr>
<th>Order ID</th>
<th>User</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($order = $result->fetch_assoc()){
?>

<tr>

<td>#<?php echo $order['id']; ?></td>

<td><?php echo $order['email']; ?></td>

<td>₱<?php echo $order['total']; ?></td>

<td><?php echo $order['status']; ?></td>

<td>

<a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn">
View
</a>

</td>

</tr>

<?php
}
?>

</table>