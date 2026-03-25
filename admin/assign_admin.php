<?php
session_start();
include("../config/db.php");

# 🔒 Only super admin
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'super_admin'){
    header("Location: ../index.php");
    exit();
}

# ✅ HANDLE ASSIGN
if(isset($_POST['assign'])){

    $user_id = $_POST['user_id'];
    $shop_id = $_POST['shop_id'];

    # Get user
    $user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

    # 🚫 Block banned users
    if(isset($user['status']) && $user['status'] == 'banned'){
        $message = "❌ Cannot assign banned user";
    }

    # 🚫 Block users already assigned
    elseif(!empty($user['shop_id'])){
        $message = "❌ User already assigned to a shop";
    }

    else{
        # ✅ Assign user as shop admin
        $conn->query("
        UPDATE users 
        SET role='shop_admin', shop_id=$shop_id 
        WHERE id=$user_id
        ");

        $message = "✅ Shop Admin Assigned!";
    }
}

# ✅ GET VALID USERS (no banned, no assigned, no super admin)
$users = $conn->query("
SELECT * FROM users 
WHERE role != 'super_admin'
AND (status IS NULL OR status='active')
AND (shop_id IS NULL OR shop_id = '')
");

# ✅ GET AVAILABLE SHOPS (no existing admin)
$shops = $conn->query("
SELECT * FROM shops 
WHERE id NOT IN (
    SELECT shop_id FROM users WHERE shop_id IS NOT NULL
)
");
?>

<h2>🏪 Assign Shop Admin</h2>

<?php if(isset($message)){ ?>
<p style="color:blue;"><strong><?php echo $message; ?></strong></p>
<?php } ?>

<form method="POST">

<label>Select User:</label><br>
<select name="user_id" required>
<option value="">-- Select User --</option>

<?php if($users->num_rows > 0){ ?>
    <?php while($u = $users->fetch_assoc()){ ?>
        <option value="<?php echo $u['id']; ?>">
            <?php echo $u['email']; ?>
        </option>
    <?php } ?>
<?php } else { ?>
    <option disabled>No available users</option>
<?php } ?>

</select>

<br><br>

<label>Select Shop:</label><br>
<select name="shop_id" required>
<option value="">-- Select Shop --</option>

<?php if($shops->num_rows > 0){ ?>
    <?php while($s = $shops->fetch_assoc()){ ?>
        <option value="<?php echo $s['id']; ?>">
            <?php echo $s['name']; ?>
        </option>
    <?php } ?>
<?php } else { ?>
    <option disabled>No available shops</option>
<?php } ?>

</select>

<br><br>

<button name="assign" style="
background:#0077be;
color:white;
padding:8px 15px;
border:none;
border-radius:5px;
cursor:pointer;
">
Assign as Shop Admin
</button>

</form>

<br>

<a href="dashboard.php">⬅ Back to Dashboard</a>