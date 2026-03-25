<?php
session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("
SELECT * FROM orders
WHERE user_id = $user_id
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Aquarium Shop</title>
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
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Bubble Animation */
        .bubble {
            position: fixed;
            bottom: -100px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            pointer-events: none;
            animation: rise 15s infinite ease-in;
            z-index: 0;
        }

        @keyframes rise {
            0% {
                bottom: -100px;
                transform: translateX(0);
            }
            100% {
                bottom: 100vh;
                transform: translateX(20px);
            }
        }

        /* Wave Effect */
        .wave-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            overflow: hidden;
            z-index: 0;
            opacity: 0.3;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='1' d='M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,181.3C672,181,768,203,864,208C960,213,1056,203,1152,186.7C1248,171,1344,149,1392,138.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
            background-size: cover;
            animation: wave 10s linear infinite;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            50% { transform: translateX(-50px); }
            100% { transform: translateX(0); }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Header Card */
        .header-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 60px 60px 30px 30px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 40, 80, 0.3),
                       inset 0 0 30px rgba(0, 180, 216, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.6);
            position: relative;
            overflow: hidden;
        }

        .header-card::before {
            content: '🐠 🐟 🐡 🐋 🐬';
            position: absolute;
            top: -20px;
            left: 0;
            width: 100%;
            font-size: 40px;
            opacity: 0.1;
            transform: rotate(-5deg);
            white-space: nowrap;
            animation: swim 30s linear infinite;
        }

        @keyframes swim {
            0% { transform: translateX(-100%) rotate(-5deg); }
            100% { transform: translateX(100%) rotate(-5deg); }
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        h1 {
            color: #023e8a;
            font-size: 2.5em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
            position: relative;
            display: inline-block;
        }

        h1::after {
            content: '🌊';
            position: absolute;
            right: -40px;
            top: 0;
            font-size: 40px;
            animation: bob 3s ease-in-out infinite;
        }

        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .nav-links {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #0077be 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #90e0ef;
            box-shadow: 0 5px 15px rgba(0, 100, 148, 0.4);
        }

        .nav-link:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
            background: linear-gradient(135deg, #0096c7 0%, #00b4d8 100%);
        }

        .nav-link.history {
            background: linear-gradient(135deg, #48cae4 0%, #0077be 100%);
        }

        /* Orders Container */
        .orders-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Order Card */
        .order-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            border-radius: 50px 50px 30px 30px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 50, 80, 0.3),
                       inset 0 0 30px rgba(0, 180, 216, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .order-card:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 20px 45px rgba(0, 100, 148, 0.4),
                       inset 0 0 40px rgba(144, 224, 239, 0.3);
        }

        /* Order Header */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ade8f4;
            flex-wrap: wrap;
            gap: 15px;
        }

        .order-title {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .order-number {
            font-size: 1.4em;
            font-weight: 700;
            color: #023e8a;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .order-number span {
            background: linear-gradient(135deg, #caf0f8 0%, #90e0ef 100%);
            padding: 8px 18px;
            border-radius: 40px;
            color: #03045e;
            border: 2px solid #48cae4;
        }

        .status-badge {
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 1em;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .status-placed {
            background: linear-gradient(135deg, #90e0ef 0%, #48cae4 100%);
            color: #023e8a;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #ffb3b3 0%, #ff8c8c 100%);
            color: white;
        }

        .status-cancelled::before {
            content: '❌';
        }

        .status-placed::before {
            content: '⏳';
        }

        /* Order Info */
        .order-info {
            display: flex;
            gap: 30px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(202, 240, 248, 0.3);
            padding: 10px 20px;
            border-radius: 30px;
            border: 1px solid #90e0ef;
        }

        .info-label {
            color: #0077be;
            font-weight: 600;
        }

        .info-value {
            font-weight: 700;
            color: #023e8a;
            background: white;
            padding: 5px 15px;
            border-radius: 30px;
        }

        .total-value {
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            padding: 8px 25px;
            border-radius: 40px;
            font-size: 1.2em;
            font-weight: 700;
        }

        /* Cancel Button */
        .cancel-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #ffb8b8;
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.3);
            margin: 15px 0;
            display: inline-block;
        }

        .cancel-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(255, 71, 87, 0.5);
            background: linear-gradient(135deg, #ff4757 0%, #ff0000 100%);
        }

        .cancel-btn::before {
            content: '⚠️';
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.5);
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        th {
            background: linear-gradient(135deg, #006994 0%, #0096c7 100%);
            color: white;
            padding: 15px;
            font-weight: 600;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0f7fa;
            color: #023e8a;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f0f9ff;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid #90e0ef;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .shop-name {
            background: #caf0f8;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            color: #023e8a;
        }

        .price {
            font-weight: 700;
            color: #0077be;
        }

        .quantity {
            background: #90e0ef;
            color: #023e8a;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 30px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 80px 80px 40px 40px;
            box-shadow: 0 20px 50px rgba(0, 50, 80, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
        }

        .empty-icon {
            font-size: 100px;
            margin-bottom: 20px;
            animation: float 4s ease-in-out infinite;
        }

        .empty-title {
            font-size: 2.2em;
            color: #023e8a;
            margin-bottom: 15px;
        }

        .empty-text {
            color: #0077be;
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 1.2em;
        }

        .shop-now-btn {
            display: inline-block;
            padding: 18px 50px;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.3em;
            transition: all 0.3s ease;
            border: 3px solid #caf0f8;
            box-shadow: 0 10px 30px rgba(0, 150, 199, 0.4);
        }

        .shop-now-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.6);
        }

        /* Fish Decorations */
        .fish {
            position: fixed;
            font-size: 40px;
            z-index: 0;
            opacity: 0.15;
            pointer-events: none;
        }

        .fish-1 {
            top: 20%;
            left: 2%;
            animation: swim-fish-1 30s linear infinite;
        }

        .fish-2 {
            bottom: 30%;
            right: 2%;
            animation: swim-fish-2 25s linear infinite;
        }

        .fish-3 {
            top: 60%;
            left: 10%;
            animation: swim-fish-3 35s linear infinite;
        }

        @keyframes swim-fish-1 {
            0% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(200px) translateY(-50px); }
            50% { transform: translateX(400px) translateY(0); }
            75% { transform: translateX(200px) translateY(50px); }
            100% { transform: translateX(0) translateY(0); }
        }

        @keyframes swim-fish-2 {
            0% { transform: translateX(0) translateY(0) scaleX(-1); }
            25% { transform: translateX(-200px) translateY(50px) scaleX(-1); }
            50% { transform: translateX(-400px) translateY(0) scaleX(-1); }
            75% { transform: translateX(-200px) translateY(-50px) scaleX(-1); }
            100% { transform: translateX(0) translateY(0) scaleX(-1); }
        }

        @keyframes swim-fish-3 {
            0% { transform: translateX(0) translateY(0); }
            33% { transform: translateX(150px) translateY(-30px); }
            66% { transform: translateX(300px) translateY(30px); }
            100% { transform: translateX(0) translateY(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            h1::after {
                display: none;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .order-info {
                flex-direction: column;
                gap: 10px;
            }
            
            th, td {
                padding: 10px;
                font-size: 0.9em;
            }
            
            .product-image {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- Bubble Animation -->
    <?php for($i = 0; $i < 30; $i++): ?>
        <div class="bubble" style="
            left: <?php echo rand(0, 100); ?>%;
            width: <?php echo rand(10, 60); ?>px;
            height: <?php echo rand(10, 60); ?>px;
            animation-delay: <?php echo rand(0, 15); ?>s;
            animation-duration: <?php echo rand(8, 25); ?>s;
        "></div>
    <?php endfor; ?>

    <!-- Swimming Fish -->
    <div class="fish fish-1">🐠</div>
    <div class="fish fish-2">🐡</div>
    <div class="fish fish-3">🐋</div>

    <!-- Wave Effect -->
    <div class="wave-container">
        <div class="wave"></div>
    </div>

    <div class="container">
        <!-- Header Card -->
        <div class="header-card">
            <div class="header-content">
                <h1>🐟 My Orders</h1>
                <div class="nav-links">
                    <a href="products.php" class="nav-link">
                        ← Back to Shop
                    </a>
                    <a href="order_history.php" class="nav-link history">
                        📜 Order History
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($order = $result->fetch_assoc()): ?>
                    <div class="order-card">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="order-title">
                                <div class="order-number">
                                    Order #<span><?php echo htmlspecialchars($order['id']); ?></span>
                                </div>
                                <div class="status-badge <?php echo $order['status'] == 'cancelled' ? 'status-cancelled' : 'status-placed'; ?>">
                                    <?php echo ucfirst($order['status']); ?>
                                </div>
                            </div>
                            <div class="order-info">
                                <div class="info-item">
                                    <span class="info-label">Total:</span>
                                    <span class="info-value total-value">₱<?php echo number_format($order['total'], 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Date:</span>
                                    <span class="info-value"><?php echo date('M d, Y', strtotime($order['created_at'] ?? 'now')); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Cancel Button (Only for placed orders) -->
                        <?php if($order['status'] == 'placed'): ?>
                            <a href="cancel_order.php?id=<?php echo $order['id']; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this order?')">
                                Cancel Order
                            </a>
                        <?php endif; ?>

                        <!-- Order Items Table -->
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Shop</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $order_id = $order['id'];
                                    $items = $conn->query("
                                        SELECT order_items.*, products.name, products.image, shops.name AS shop_name
                                        FROM order_items
                                        JOIN products ON order_items.product_id = products.id
                                        JOIN shops ON products.shop_id = shops.id
                                        WHERE order_items.order_id = $order_id
                                    ");

                                    while($item = $items->fetch_assoc()):
                                        $image = $item['image'];
                                        if($image == "" || !file_exists("images/".$image)){
                                            $image = "no-image.png";
                                        }
                                    ?>
                                    <tr>
                                        <td>
                                            <img src="images/<?php echo htmlspecialchars($image); ?>" class="product-image" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($item['name']); ?></strong></td>
                                        <td><span class="shop-name"><?php echo htmlspecialchars($item['shop_name']); ?></span></td>
                                        <td><span class="quantity">x<?php echo $item['quantity']; ?></span></td>
                                        <td class="price">₱<?php echo number_format($item['price'], 2); ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">🐚</div>
                    <h2 class="empty-title">No Orders Yet!</h2>
                    <p class="empty-text">
                        Your aquarium is empty! Start shopping for amazing fish and pets.
                    </p>
                    <a href="products.php" class="shop-now-btn">
                        Browse Products 🐟
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>