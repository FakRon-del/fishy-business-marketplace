<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("../config/db.php");

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if($result->num_rows > 0){

$user = $result->fetch_assoc();

if(password_verify($password,$user['password'])){

$_SESSION['user_id']=$user['id'];

header("Location: ../index.php");
exit();

}else{
echo "Incorrect password";
}

}else{
echo "User not found";
}

}
?>
<link rel="stylesheet" href="../css/style.css">

<div class="form-container">

<h2>Login</h2>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login" class="btn">Login</button>

</form>

<p>No account?</p>

<a href="register.php">Register Here</a>

</div>