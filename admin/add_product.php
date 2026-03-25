<?php
session_start();
include("../config/db.php");

if(isset($_POST['add'])){

$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$shop_id = $_SESSION['shop_id'];
$image = $_FILES['image']['name'];

move_uploaded_file($_FILES['image']['tmp_name'], "../images/".$image);

$conn->query("INSERT INTO products
(name,description,price,image,stock,shop_id)
VALUES
('$name','$description','$price','$image','$stock','$shop_id')");

echo "Product Added!";
}
?>

<h2>Add Fish Product</h2>

<form method="POST" enctype="multipart/form-data">

Name:<br>
<input type="text" name="name"><br><br>

Description:<br>
<textarea name="description"></textarea><br><br>

Price:<br>
<input type="number" name="price"><br><br>

Stock:<br>
<input type="number" name="stock"><br><br>

Shop:<br>
<?php
$shop_id = $_SESSION['shop_id'];
?>
<?php

$shops = $conn->query("SELECT * FROM shops");

while($shop = $shops->fetch_assoc()){

echo "<option value='".$shop['id']."'>".$shop['name']."</option>";

}

?>

</select>

<br><br>

Fish Image:<br>
<input type="file" name="image"><br><br>

<button name="add">Add Product</button>

</form>