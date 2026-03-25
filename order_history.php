<?php
session_start();
include("config/db.php");

$user_id = $_SESSION['user_id'];

$result = $conn->query("
SELECT * FROM orders
WHERE user_id = $user_id
AND status = 'cancelled'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquarium - Cancelled Orders</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
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
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Header Card - Aquarium Style */
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

        h1 {
            color: #023e8a;
            font-size: 2.5em;
            margin-bottom: 15px;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #0077be 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #90e0ef;
            box-shadow: 0 5px 15px rgba(0, 100, 148, 0.4);
            font-size: 1.1em;
        }

        .back-link:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
            background: linear-gradient(135deg, #0096c7 0%, #00b4d8 100%);
        }

        .back-link::before {
            content: '🐟';
            font-size: 1.2em;
        }

        /* Orders List */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Order Card - Aquarium Themed */
        .order-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            border-radius: 50px 50px 30px 30px;
            padding: 25px;
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

        /* Underwater Plants Decoration */
        .order-card::before {
            content: '🌿 🌱 🌊';
            position: absolute;
            bottom: -10px;
            right: -10px;
            font-size: 50px;
            opacity: 0.2;
            transform: rotate(10deg);
            z-index: 0;
        }

        .order-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #48cae4, #0096c7, #023e8a, #0096c7, #48cae4);
        }

        /* Order Header */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #ade8f4;
            position: relative;
            z-index: 1;
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
            font-size: 0.9em;
            border: 2px solid #48cae4;
            box-shadow: inset 0 2px 5px rgba(255, 255, 255, 0.8);
        }

        .order-number::before {
            content: '🐠';
            font-size: 1.2em;
            animation: swim-fish 5s ease-in-out infinite;
        }

        @keyframes swim-fish {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            50% { transform: translateX(5px) rotate(5deg); }
        }

        .status-badge {
            background: linear-gradient(135deg, #ffb3b3 0%, #ff8c8c 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 40px;
            font-size: 1em;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(255, 0, 0, 0.2);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .status-badge::before {
            content: '❌';
            font-size: 1em;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.2));
        }

        /* Order Details */
        .order-details {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: rgba(202, 240, 248, 0.3);
            border-radius: 20px;
            border: 1px solid #90e0ef;
        }

        .detail-label {
            font-size: 1em;
            color: #0077be;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-label::before {
            content: '🌊';
            font-size: 1.2em;
        }

        .detail-value {
            font-size: 1.3em;
            font-weight: 700;
            color: #023e8a;
            background: white;
            padding: 5px 15px;
            border-radius: 30px;
            box-shadow: 0 2px 8px rgba(0, 100, 148, 0.2);
        }

        .total-value {
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            padding: 8px 25px;
            border-radius: 40px;
            font-size: 1.5em;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            border: 2px solid #caf0f8;
        }

        .cancelled-date {
            background: linear-gradient(135deg, #caf0f8 0%, #90e0ef 100%);
            padding: 15px 20px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #03045e;
            font-weight: 600;
            border: 2px dashed #48cae4;
            margin-top: 5px;
        }

        .cancelled-date::before {
            content: '⏰';
            font-size: 1.4em;
            animation: tick 2s ease-in-out infinite;
        }

        @keyframes tick {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            75% { transform: rotate(-10deg); }
        }

        /* Order Footer - Simplified */
        .order-footer {
            margin-top: 15px;
            text-align: right;
            font-size: 0.9em;
            color: #0077be;
            font-style: italic;
            border-top: 2px dotted #90e0ef;
            padding-top: 15px;
        }

        .order-footer::after {
            content: '🐚';
            font-size: 1.5em;
            margin-left: 10px;
            opacity: 0.6;
        }

        /* Empty State - Aquarium Theme */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 80px 80px 40px 40px;
            box-shadow: 0 20px 50px rgba(0, 50, 80, 0.3);
            border: 3px solid rgba(255, 255, 255, 0.9);
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '🐟🐠🐡🐋🐬🦑';
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            font-size: 30px;
            opacity: 0.1;
            animation: school 20s linear infinite;
        }

        @keyframes school {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .empty-icon {
            font-size: 100px;
            margin-bottom: 20px;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .empty-title {
            font-size: 2.2em;
            color: #023e8a;
            margin-bottom: 15px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
        }

        .empty-text {
            color: #0077be;
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 1.2em;
            background: rgba(144, 224, 239, 0.2);
            padding: 20px;
            border-radius: 50px;
            border: 2px solid #90e0ef;
        }

        .btn-shop {
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
            position: relative;
            overflow: hidden;
        }

        .btn-shop::before {
            content: '🐟';
            position: absolute;
            left: -30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .btn-shop:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 180, 216, 0.6);
            background: linear-gradient(135deg, #4ad6ff 0%, #00b4d8 100%);
            padding-left: 70px;
        }

        .btn-shop:hover::before {
            left: 20px;
        }

        /* Fish Decorations */
        .fish {
            position: fixed;
            font-size: 30px;
            z-index: 0;
            opacity: 0.2;
            pointer-events: none;
        }

        .fish-1 {
            top: 10%;
            left: 5%;
            animation: fish-swim-1 25s linear infinite;
        }

        .fish-2 {
            bottom: 20%;
            right: 5%;
            animation: fish-swim-2 20s linear infinite;
        }

        @keyframes fish-swim-1 {
            0% { transform: translateX(0) translateY(0) rotate(0deg); }
            25% { transform: translateX(100px) translateY(-50px) rotate(10deg); }
            50% { transform: translateX(200px) translateY(0) rotate(0deg); }
            75% { transform: translateX(100px) translateY(50px) rotate(-10deg); }
            100% { transform: translateX(0) translateY(0) rotate(0deg); }
        }

        @keyframes fish-swim-2 {
            0% { transform: translateX(0) translateY(0) rotate(180deg); }
            25% { transform: translateX(-100px) translateY(50px) rotate(170deg); }
            50% { transform: translateX(-200px) translateY(0) rotate(180deg); }
            75% { transform: translateX(-100px) translateY(-50px) rotate(190deg); }
            100% { transform: translateX(0) translateY(0) rotate(180deg); }
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                padding: 0 10px;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .detail-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            h1 {
                font-size: 2em;
            }
            
            h1::after {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Bubble Animation -->
    <?php for($i = 0; $i < 20; $i++): ?>
        <div class="bubble" style="
            left: <?php echo rand(0, 100); ?>%;
            width: <?php echo rand(10, 50); ?>px;
            height: <?php echo rand(10, 50); ?>px;
            animation-delay: <?php echo rand(0, 10); ?>s;
            animation-duration: <?php echo rand(10, 25); ?>s;
        "></div>
    <?php endfor; ?>

    <!-- Swimming Fish -->
    <div class="fish fish-1">🐠</div>
    <div class="fish fish-2">🐡</div>

    <!-- Wave Effect -->
    <div class="wave-container">
        <div class="wave"></div>
    </div>

    <div class="container">
        <!-- Header Section -->
        <div class="header-card">
            <h1>
                Cancelled Orders
            </h1>
            <a href="my_orders.php" class="back-link">
                Back to My Orders
            </a>
        </div>

        <!-- Orders List -->
        <div class="orders-list">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($order = $result->fetch_assoc()): ?>
                    <div class="order-card">
                        <!-- Order Header -->
                        <div class="order-header">
                            <div class="order-number">
                                Order #<span><?php echo htmlspecialchars($order['id']); ?></span>
                            </div>
                            <div class="status-badge">
                                Cancelled
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="order-details">
                            <div class="detail-item">
                                <span class="detail-label">Total Amount</span>
                                <span class="detail-value total-value">
                                    ₱<?php echo number_format($order['total'], 2); ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Order Date</span>
                                <span class="detail-value">
                                    <?php echo date('M d, Y', strtotime($order['created_at'] ?? 'now')); ?>
                                </span>
                            </div>
                            <?php if (isset($order['cancelled_at']) || isset($order['updated_at'])): ?>
                            <div class="cancelled-date">
                                Cancelled on: <?php echo date('F d, Y \a\t h:i A', strtotime($order['updated_at'] ?? $order['created_at'])); ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Simple Footer -->
                        <div class="order-footer">
                            Order cancelled
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">🐚</div>
                    <h2 class="empty-title">No Cancelled Orders</h2>
                    <p class="empty-text">
                        Your coral reef is clear! No cancelled orders found.<br>
                    </p>
                    <a href="shop.php" class="btn-shop">
                        Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>