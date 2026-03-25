<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$items = $conn->query("
    SELECT cart_items.*, products.name, products.price, products.image
    FROM cart_items
    JOIN carts ON cart_items.cart_id = carts.id
    JOIN products ON cart_items.product_id = products.id
    WHERE carts.user_id = $user_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart - Aquarium Shop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #006994 0%, #00b4d8 50%, #90e0ef 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            font-size: 14px; /* Smaller base font size */
        }

        /* Bubble Animation - Smaller */
        .bubble {
            position: fixed;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            pointer-events: none;
            animation: rise 15s infinite ease-in;
            z-index: 0;
        }

        @keyframes rise {
            0% { bottom: -100px; transform: translateX(0); }
            100% { bottom: 100vh; transform: translateX(20px); }
        }

        /* Wave Effect - Subtle */
        .wave-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            overflow: hidden;
            z-index: 0;
            opacity: 0.2;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,181.3C672,181,768,203,864,208C960,213,1056,203,1152,186.7C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            animation: wave 10s linear infinite;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            50% { transform: translateX(-50px); }
            100% { transform: translateX(0); }
        }

        /* Navbar - Compact */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #90e0ef;
            box-shadow: 0 2px 10px rgba(0, 40, 80, 0.1);
            position: relative;
            z-index: 2;
        }

        .navbar h2 {
            color: #023e8a;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-links {
            display: flex;
            gap: 8px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 5px 12px;
            background: linear-gradient(135deg, #0077be 0%, #0096c7 100%);
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.85em;
            transition: all 0.3s ease;
            border: 1px solid #90e0ef;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .nav-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 180, 216, 0.3);
        }

        /* Container - Compact */
        .container {
            max-width: 1400px;
            margin: 15px auto;
            padding: 0 15px;
            position: relative;
            z-index: 1;
        }

        /* Page Header - Compact */
        .page-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0, 40, 80, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        h1 {
            color: #023e8a;
            font-size: 1.8em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        h1::before {
            content: '🛒';
            font-size: 1.2em;
        }

        .continue-shopping {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 18px;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.9em;
            transition: all 0.3s ease;
            border: 1px solid #caf0f8;
        }

        /* Cart Grid - More compact */
        .cart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }

        /* Cart Card - Compact */
        .cart-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 30px;
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0, 50, 80, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .cart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 100, 148, 0.25);
        }

        .cart-card::before {
            content: '🐟';
            position: absolute;
            bottom: -5px;
            right: -5px;
            font-size: 50px;
            opacity: 0.05;
            transform: rotate(15deg);
        }

        .cart-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 20px;
            border: 2px solid #90e0ef;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .cart-card:hover img {
            transform: scale(1.02);
        }

        .cart-card h3 {
            color: #023e8a;
            font-size: 1.1em;
            margin: 10px 0 5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .cart-card h3::before {
            content: '🐠';
            font-size: 0.9em;
        }

        .price {
            color: white;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            font-size: 0.95em;
            border: 1px solid #caf0f8;
            margin: 5px 0;
        }

        /* Quantity Controls - Compact */
        .qty {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 10px 0;
            background: rgba(202, 240, 248, 0.3);
            padding: 6px;
            border-radius: 30px;
            border: 1px solid #90e0ef;
        }

        .qty a {
            padding: 4px 10px;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1em;
            transition: all 0.3s ease;
            border: 1px solid #caf0f8;
            min-width: 30px;
            text-align: center;
        }

        .qty a:hover {
            transform: scale(1.05);
        }

        .qty strong {
            font-size: 1em;
            color: #023e8a;
            background: white;
            padding: 3px 12px;
            border-radius: 20px;
            border: 1px solid #90e0ef;
        }

        .item-total {
            font-size: 1em;
            font-weight: 600;
            color: #023e8a;
            margin: 5px 0;
        }

        .remove {
            color: white;
            text-decoration: none;
            background: linear-gradient(135deg, #ff8c8c 0%, #ff6b6b 100%);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85em;
            transition: all 0.3s ease;
            border: 1px solid #ffb8b8;
            display: inline-block;
            margin-top: 8px;
            width: fit-content;
            align-self: center;
        }

        .remove:hover {
            transform: scale(1.02);
        }

        .remove::before {
            content: '🗑️';
            margin-right: 3px;
            font-size: 0.9em;
        }

        /* Empty Cart - Compact */
        .empty-cart {
            text-align: center;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 40px;
            box-shadow: 0 15px 35px rgba(0, 50, 80, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.9);
            grid-column: 1 / -1;
        }

        .empty-cart .icon {
            font-size: 60px;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #023e8a;
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        /* Summary Section - Compact */
        .summary {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            border-radius: 30px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 10px 25px rgba(0, 50, 80, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.8);
            text-align: center;
        }

        .summary h2 {
            color: #023e8a;
            font-size: 1.5em;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .total-amount {
            color: white;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            padding: 5px 20px;
            border-radius: 30px;
            border: 2px solid #caf0f8;
            font-size: 1.1em;
        }

        .btn {
            display: inline-block;
            padding: 12px 35px;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            border: 2px solid #caf0f8;
            box-shadow: 0 5px 15px rgba(0, 150, 199, 0.3);
        }

        .btn:hover {
            transform: scale(1.02);
        }

        /* Fish Decorations - Subtle */
        .fish {
            position: fixed;
            font-size: 35px;
            z-index: 0;
            opacity: 0.08;
            pointer-events: none;
        }

        .fish-1 {
            top: 15%;
            left: 1%;
            animation: swim-around-1 40s linear infinite;
        }

        .fish-2 {
            bottom: 20%;
            right: 1%;
            animation: swim-around-2 35s linear infinite;
        }

        @keyframes swim-around-1 {
            0% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(200px) translateY(-50px); }
            50% { transform: translateX(400px) translateY(0); }
            75% { transform: translateX(200px) translateY(50px); }
            100% { transform: translateX(0) translateY(0); }
        }

        @keyframes swim-around-2 {
            0% { transform: translateX(0) translateY(0) scaleX(-1); }
            25% { transform: translateX(-200px) translateY(50px) scaleX(-1); }
            50% { transform: translateX(-400px) translateY(0) scaleX(-1); }
            75% { transform: translateX(-200px) translateY(-50px) scaleX(-1); }
            100% { transform: translateX(0) translateY(0) scaleX(-1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 8px;
                text-align: center;
                padding: 8px;
            }
            
            .nav-links {
                justify-content: center;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            h1 {
                font-size: 1.5em;
            }
            
            .cart-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .cart-card {
                max-width: 280px;
                margin: 0 auto;
            }
            
            .fish {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Bubble Animation - Fewer bubbles -->
    <?php for($i = 0; $i < 15; $i++): ?>
        <div class="bubble" style="
            left: <?php echo rand(0, 100); ?>%;
            width: <?php echo rand(8, 30); ?>px;
            height: <?php echo rand(8, 30); ?>px;
            animation-delay: <?php echo rand(0, 15); ?>s;
            animation-duration: <?php echo rand(8, 25); ?>s;
        "></div>
    <?php endfor; ?>

    <!-- Swimming Fish - Subtle -->
    <div class="fish fish-1">🐠</div>
    <div class="fish fish-2">🐡</div>

    <!-- Wave Effect -->
    <div class="wave-container">
        <div class="wave"></div>
    </div>

    <!-- Navbar -->
    <div class="navbar">
        <h2>🐟 Aquarium Shop</h2>
        <div class="nav-links">
            <a href="../products.php" class="nav-link">🐠 Browse</a>
            <a href="../my_orders.php" class="nav-link">📦 Orders</a>
            <a href="../auth/logout.php" class="nav-link">🚪 Logout</a>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>My Cart</h1>
                <a href="../products.php" class="continue-shopping">
                    ← Continue Shopping
                </a>
            </div>
        </div>

        <!-- Cart Items Grid -->
        <div class="cart-grid">
            <?php
            $total = 0;
            $hasItems = false;

            while($item = $items->fetch_assoc()){
                $hasItems = true;

                $image = $item['image'];
                if($image == "" || !file_exists("../images/".$image)){
                    $image = "no-image.png";
                }

                $item_total = $item['price'] * $item['quantity'];
                $total += $item_total;
            ?>

                <div class="cart-card">
                    <img src="../images/<?php echo htmlspecialchars($image); ?>" 
                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                    
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    
                    <p class="price">₱<?php echo number_format($item['price'], 2); ?></p>
                    
                    <div class="qty">
                        <a href="update_quantity.php?action=decrease&id=<?php echo $item['id']; ?>">−</a>
                        <strong><?php echo $item['quantity']; ?></strong>
                        <a href="update_quantity.php?action=increase&id=<?php echo $item['id']; ?>">+</a>
                    </div>
                    
                    <p class="item-total">₱<?php echo number_format($item_total, 2); ?></p>
                    
                    <a href="remove_from_cart.php?id=<?php echo $item['id']; ?>" 
                       class="remove" 
                       onclick="return confirm('Remove this item?')">
                        Remove
                    </a>
                </div>

            <?php } ?>
        </div>

        <!-- Empty Cart Message -->
        <?php if(!$hasItems): ?>
            <div class="empty-cart">
                <div class="icon">🫧</div>
                <p>Your cart is empty</p>
                <a href="../products.php" class="btn" style="padding: 10px 25px; font-size: 1em;">
                    Start Shopping 🐟
                </a>
            </div>
        <?php endif; ?>

        <!-- Cart Summary -->
        <?php if($hasItems): ?>
            <div class="summary">
                <h2>
                    Total: <span class="total-amount">₱<?php echo number_format($total, 2); ?></span>
                </h2>
                <a href="checkout.php" class="btn">Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>