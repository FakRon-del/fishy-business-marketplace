<?php
include("../config/db.php");

$id = $_GET['id'];

$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if(isset($_POST['update'])){

$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];

$conn->query("
UPDATE products
SET name='$name', price='$price', description='$description'
WHERE id=$id
");

header("Location: products.php");

}
?>

<h2>Edit Product</h2>

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

<button name="update">Update Product</button>

</form>