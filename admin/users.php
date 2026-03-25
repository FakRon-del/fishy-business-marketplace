<?php
session_start();
include("../config/db.php");

# 🔒 Only super admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin'){
    header("Location: ../index.php");
    exit();
}

# ✅ REMOVE USER FROM SHOP
if(isset($_POST['remove_shop'])){
    $user_id = $_POST['remove_shop_id'];

    $conn->query("
    UPDATE users 
    SET shop_id=NULL, role='customer'
    WHERE id=$user_id
    ");

    $message = "✅ User removed from shop";
}

# ✅ MAKE SHOP ADMIN (manual promotion)
if(isset($_POST['make_admin'])){
    $user_id = $_POST['make_admin_id'];

    $conn->query("
    UPDATE users 
    SET role='shop_admin'
    WHERE id=$user_id
    ");

    $message = "✅ User promoted to Shop Admin";
}

# FETCH USERS
$users = $conn->query("SELECT * FROM users");
?>

<h1>👤 Manage Users</h1>

<?php if(isset($message)){ ?>
<p style="color:blue;"><strong><?php echo $message; ?></strong></p>
<?php } ?>

<hr>

<h2>👤 Users</h2>

<?php while($user = $users->fetch_assoc()){ ?>

<div style="
background:white;
padding:15px;
margin:10px;
border-radius:15px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
">

<p><strong><?php echo $user['email']; ?></strong></p>

<p>Role: <?php echo $user['role']; ?></p>

<p>Status: 
<?php echo isset($user['status']) ? $user['status'] : 'active'; ?>
</p>

<?php
# ✅ GET SHOP NAME
$shop_name = "None";

if(!empty($user['shop_id'])){
    $shop = $conn->query("SELECT name FROM shops WHERE id=".$user['shop_id'])->fetch_assoc();
    $shop_name = $shop['name'] ?? "Unknown";
}
?>

<p>Shop: <?php echo $shop_name; ?></p>

<!-- ✅ BAN / UNBAN -->
<?php if(!isset($user['status']) || $user['status'] == 'active'){ ?>
<a href="ban_user.php?id=<?php echo $user['id']; ?>" style="color:red;">Ban</a>
<?php } else { ?>
<a href="unban_user.php?id=<?php echo $user['id']; ?>" style="color:green;">Unban</a>
<?php } ?>

<br><br>

<!-- ✅ REMOVE FROM SHOP -->
<?php if(!empty($user['shop_id'])){ ?>
<form method="POST" style="display:inline;">
<input type="hidden" name="remove_shop_id" value="<?php echo $user['id']; ?>">
<button name="remove_shop" style="background:red;color:white;border:none;padding:5px 10px;border-radius:5px;">
Remove from Shop
</button>
</form>
<?php } ?>

<!-- ✅ MAKE SHOP ADMIN -->
<form method="POST" style="display:inline;">
<input type="hidden" name="make_admin_id" value="<?php echo $user['id']; ?>">
<button name="make_admin" style="margin-left:5px;">
Make Shop Admin
</button>
</form>

</div>

<?php } ?>

<hr>

<a href="dashboard.php">⬅ Back to Dashboard</a>