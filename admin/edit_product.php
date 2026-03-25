<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])){

$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];
$stock = $_POST['stock'];

$conn->query("
UPDATE products 
SET 
name='$name',
price='$price',
description='$description',
stock='$stock'
WHERE id=$id
");

# ✅ REDIRECT TO SHOP ADMIN DASHBOARD
header("Location: shop_dashboard.php");
exit();
}
?>

<h2>Edit Product</h2>

<!-- 🔙 BACK BUTTON -->
<a href="shop_dashboard.php" style="
display:inline-block;
margin-bottom:10px;
padding:8px 15px;
background:#0096c7;
color:white;
border-radius:20px;
text-decoration:none;
">
← Back to Dashboard
</a>

<form method="POST">

Name:<br>
<input type="text" name="name" value="<?php echo $product['name']; ?>">
<br><br>

Description:<br>
<textarea name="description"><?php echo $product['description']; ?></textarea>
<br><br>

Price:<br>
<input type="number" name="price" value="<?php echo $product['price']; ?>">
<br><br>

Stock:<br>
<input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
<br><br>

<button name="update">Update Product</button>

</form>