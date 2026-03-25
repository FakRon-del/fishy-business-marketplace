<?php
session_start();

if(isset($_SESSION['role']) && $_SESSION['role'] == 'super_admin'){
    header("Location: admin/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fishy Business - Aquarium Shop</title>
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
            font-size: 14px;
        }

        /* Bubble Animation - Subtle */
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
            padding: 8px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #90e0ef;
            box-shadow: 0 2px 10px rgba(0, 40, 80, 0.1);
            position: relative;
            z-index: 2;
        }

        .logo {
            color: #023e8a;
            font-size: 1.3em;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 700;
        }

        .logo::after {
            content: '🌊';
            font-size: 1em;
            animation: bob 3s ease-in-out infinite;
        }

        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        .nav-links {
            display: flex;
            gap: 8px;
        }

        .nav-links a {
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

        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 180, 216, 0.3);
        }

        /* Hero Section - Compact */
        .hero {
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px 20px;
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-content {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 40, 80, 0.3),
                       inset 0 0 30px rgba(0, 180, 216, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.6);
            max-width: 700px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .hero-content::before {
            content: '🐠 🐟 🐡 🐋 🐬';
            position: absolute;
            top: -15px;
            left: 0;
            width: 100%;
            font-size: 40px;
            opacity: 0.05;
            transform: rotate(-5deg);
            white-space: nowrap;
            animation: swim 30s linear infinite;
        }

        @keyframes swim {
            0% { transform: translateX(-100%) rotate(-5deg); }
            100% { transform: translateX(100%) rotate(-5deg); }
        }

        .hero h1 {
            color: #023e8a;
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .hero h1::before {
            content: '🐟';
            animation: swim-fish 5s ease-in-out infinite;
        }

        .hero h1::after {
            content: '🐠';
            animation: swim-fish 5s ease-in-out infinite reverse;
        }

        @keyframes swim-fish {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            50% { transform: translateX(5px) rotate(5deg); }
        }

        .hero p {
            color: #023e8a;
            font-size: 1.1em;
            margin-bottom: 30px;
            line-height: 1.6;
            background: rgba(202, 240, 248, 0.3);
            padding: 15px 25px;
            border-radius: 40px;
            border: 1px solid #90e0ef;
            display: inline-block;
        }

        .btn {
            display: inline-block;
            padding: 12px 40px;
            background: linear-gradient(135deg, #48cae4 0%, #0096c7 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            border: 2px solid #caf0f8;
            box-shadow: 0 5px 15px rgba(0, 150, 199, 0.4);
            margin-top: 15px;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 180, 216, 0.6);
            background: linear-gradient(135deg, #4ad6ff 0%, #00b4d8 100%);
        }

        .btn::before {
            content: '→';
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .btn:hover::before {
            transform: translateX(5px);
        }

        /* Features Section - Compact */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
            width: 100%;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 30px;
            padding: 20px;
            box-shadow: 0 10px 25px rgba(0, 50, 80, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 100, 148, 0.25);
        }

        .feature-card::before {
            content: '🐟';
            position: absolute;
            bottom: -5px;
            right: -5px;
            font-size: 50px;
            opacity: 0.05;
            transform: rotate(15deg);
        }

        .feature-icon {
            font-size: 2.2em;
            margin-bottom: 10px;
        }

        .feature-card h3 {
            color: #023e8a;
            font-size: 1.1em;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .feature-card p {
            color: #0077be;
            font-size: 0.9em;
            line-height: 1.4;
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
            top: 20%;
            left: 2%;
            animation: swim-around-1 40s linear infinite;
        }

        .fish-2 {
            bottom: 30%;
            right: 2%;
            animation: swim-around-2 35s linear infinite;
        }

        .fish-3 {
            top: 60%;
            left: 5%;
            animation: swim-around-3 45s linear infinite;
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

        @keyframes swim-around-3 {
            0% { transform: translateX(0) translateY(0); }
            33% { transform: translateX(300px) translateY(-30px); }
            66% { transform: translateX(600px) translateY(30px); }
            100% { transform: translateX(0) translateY(0); }
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
                flex-wrap: wrap;
            }
            
            .hero-content {
                padding: 25px;
            }
            
            .hero h1 {
                font-size: 1.8em;
            }
            
            .hero p {
                font-size: 1em;
                padding: 12px 20px;
            }
            
            .features {
                grid-template-columns: 1fr;
                gap: 15px;
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
    <div class="fish fish-3">🐋</div>

    <!-- Wave Effect -->
    <div class="wave-container">
        <div class="wave"></div>
    </div>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">🐟 Fishy Business</div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])){ ?>
                <a href="products.php">🐠 Browse Fish</a>
                <a href="cart/view_cart.php">🛒 Cart</a>
                <a href="my_orders.php">📦 Orders</a>
                <a href="auth/logout.php">🚪 Logout</a>
            <?php } else { ?>
                <a href="auth/login.php">🔐 Login</a>
                <a href="auth/register.php">📝 Register</a>
            <?php } ?>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to Fishy Business</h1>
            
            <p>Your online marketplace for fish, aquariums, and aquatic supplies.</p>
            
            <?php if(isset($_SESSION['user_id'])){ ?>
                <a href="products.php" class="btn">Browse Fish</a>
            <?php } else { ?>
                <a href="auth/register.php" class="btn">Start Shopping</a>
            <?php } ?>

            <!-- Features Section -->
            <div class="features">
                <div class="feature-card">
                    <div class="feature-icon">🐠</div>
                    <h3>Live Fish</h3>
                    <p>Freshwater & saltwater fish delivered to your door</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏪</div>
                    <h3>Local Shops</h3>
                    <p>Connect with trusted local aquarium stores</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3>Fast Delivery</h3>
                    <p>Safe and quick shipping for all aquatic life</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>