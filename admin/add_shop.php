<?php
session_start();
include("../config/db.php");

if(isset($_POST['add'])){

$name = $_POST['name'];
$description = $_POST['description'];

$conn->query("INSERT INTO shops (name,description)
VALUES ('$name','$description')");

echo "Shop Created Successfully!";

}
?>

<h2>Create Shop</h2>

<form method="POST">

Shop Name:<br>
<input type="text" name="name" required>
<br><br>

Description:<br>
<textarea name="description"></textarea>
<br><br>

<button name="add">Create Shop</button>

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>