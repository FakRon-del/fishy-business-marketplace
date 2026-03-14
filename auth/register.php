<?php
include("../config/db.php");

if(isset($_POST['register'])){

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$conn->query("INSERT INTO users(name,email,password)
VALUES('$name','$email','$password')");
}
?>
<link rel="stylesheet" href="../css/style.css">

<div class="form-container">

<h2>Create Account</h2>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button name="register" class="btn">Register</button>

</form>

<p>Already have an account?</p>

<a href="login.php">Login</a>

</div>