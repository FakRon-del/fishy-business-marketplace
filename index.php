<?php
session_start();
?>

<link rel="stylesheet" href="css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<div class="navbar">

<h2 class="logo">🐟 Fishy Business</h2>

<div class="nav-links">

<?php if(isset($_SESSION['user_id'])){ ?>

<a href="products.php">Browse Fish</a>
<a href="cart/view_cart.php">Cart</a>
<a href="my_orders.php">My Orders</a>
<a href="auth/logout.php">Logout</a>

<?php } else { ?>

<a href="auth/login.php">Login</a>
<a href="auth/register.php">Register</a>

<?php } ?>

</div>

</div>


<div class="hero">

<h1>Welcome to Fishy Business</h1>

<p>Your online marketplace for fish, aquariums, and aquatic supplies.</p>

<?php if(isset($_SESSION['user_id'])){ ?>

<a href="products.php" class="btn">Browse Fish</a>

<?php } else { ?>

<a href="auth/register.php" class="btn">Start Shopping</a>



<?php } ?>

</div>