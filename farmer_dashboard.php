<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$farmer_id = $_SESSION['user_id'] ?? 0;
$userName = $_SESSION['username'] ?? 'Farmer';

// Stats
$totalCrops = $conn->query("SELECT COUNT(*) AS total FROM crops WHERE farmer_id = $farmer_id")->fetch_assoc()['total'];
$totalOrders = $conn->query("
    SELECT COUNT(*) AS total 
    FROM orders o 
    LEFT JOIN crops c ON o.crop_id = c.crop_id 
    WHERE c.farmer_id = $farmer_id
")->fetch_assoc()['total'];
$totalEarnings = $conn->query("
    SELECT SUM(o.quantity * c.price_per_kg) AS earnings
    FROM orders o
    LEFT JOIN crops c ON o.crop_id = c.crop_id
    WHERE c.farmer_id = $farmer_id AND o.status != 'cancelled'
")->fetch_assoc()['earnings'] ?? 0;

// Unread Messages
$unreadCount = $conn->query("
    SELECT COUNT(*) AS total 
    FROM messages 
    WHERE receiver_id = $farmer_id AND seen = 0
")->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Farmer Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body{font-family:'Poppins',sans-serif;background:#f0fdf4;margin:0;padding:0;}
.main-wrapper{display:flex;min-height:100vh;}
.sidebar{width:100px;background:linear-gradient(to bottom,#14532d,#166534,#22c55e);display:flex;flex-direction:column;align-items:center;padding:30px 0;border-top-right-radius:40px;border-bottom-right-radius:40px;}
.profile-box{width:60px;height:60px;background:white;color:#166534;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:bold;position:relative;margin-bottom:40px;text-decoration:none;}
.profile-dot{width:14px;height:14px;background:#22c55e;border-radius:50%;border:2px solid white;position:absolute;top:-2px;right:-2px;}
.side-btn{width:58px;height:58px;border-radius:18px;background:rgba(255,255,255,0.15);color:white;border:none;font-size:20px;margin-bottom:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:0.3s ease;}
.side-btn:hover{background:rgba(255,255,255,0.25);transform:translateY(-2px);}
.active-btn{background:white;color:#166534;}
.logout-btn{background:#ef4444;margin-top:auto;}
.logout-btn:hover{background:#dc2626;}
.content{flex:1;padding:30px;}
.top-banner{background:linear-gradient(135deg,#166534,#22c55e);border-radius:30px;padding:35px;color:white;display:flex;justify-content:space-between;align-items:center;margin-bottom:30px;box-shadow:0 15px 35px rgba(34,197,94,0.25);}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:25px;margin-bottom:30px;}
.stat-card{background:white;border-radius:25px;padding:25px;box-shadow:0 10px 30px rgba(0,0,0,0.08);transition:0.3s ease;}
.stat-card:hover{transform:translateY(-5px);}
.stat-icon{width:65px;height:65px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:18px;}
.tips-section{margin-top:30px;}
.tips-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;}
.tip-card{background:white;border-radius:25px;padding:25px;box-shadow:0 10px 30px rgba(0,0,0,0.08);transition:0.3s ease;}
.tip-card:hover{transform:translateY(-5px);}
.tip-icon{font-size:28px;margin-bottom:12px;color:#14532d;}
.action-card{text-decoration:none;background:white;border-radius:25px;padding:25px;box-shadow:0 10px 30px rgba(0,0,0,0.08);transition:0.3s ease;display:block;margin-bottom:20px;}
.action-card:hover{transform:translateY(-5px);}
</style>
</head>
<body>

<div class="main-wrapper">

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="user_profile.php" class="profile-box"><?= strtoupper(substr($userName,0,1)) ?><div class="profile-dot"></div></a>
    <button onclick="window.location.href='farmer_dashboard.php'" class="side-btn active-btn"><i class="fas fa-house"></i></button>
    <button onclick="window.location.href='add_crop.php'" class="side-btn"><i class="fas fa-plus-circle"></i></button>
    <button onclick="window.location.href='farmer_orders.php'" class="side-btn"><i class="fas fa-cart-shopping"></i></button>
    <a href="farmer_messages.php" class="side-btn" style="position:relative;"><i class="fas fa-comments"></i>
      <?php if($unreadCount>0): ?>
        <span style="position:absolute;top:-5px;right:-5px;background:red;color:white;font-size:12px;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold;"><?= $unreadCount ?></span>
      <?php endif; ?>
    </a>
    <button onclick="window.location.href='logout.php'" class="side-btn logout-btn"><i class="fas fa-right-from-bracket"></i></button>
  </div>

  <!-- Main Content -->
  <div class="content">

    <div class="top-banner">
      <div>
        <h1 class="text-4xl font-bold mb-2">Hello, <?= htmlspecialchars($userName) ?> 👋</h1>
        <p class="text-green-100 text-lg">Welcome back! Track your crops, orders, and earnings easily.</p>
      </div>
      <div class="hidden md:block text-7xl opacity-30"><i class="fas fa-leaf"></i></div>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon bg-green-100 text-green-700"><i class="fas fa-seedling"></i></div>
        <h3 class="text-gray-500 text-lg">Total Crops</h3>
        <h1 class="text-4xl font-bold text-green-800 mt-2"><?= $totalCrops ?></h1>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-yellow-100 text-yellow-600"><i class="fas fa-cart-shopping"></i></div>
        <h3 class="text-gray-500 text-lg">Total Orders</h3>
        <h1 class="text-4xl font-bold text-green-800 mt-2"><?= $totalOrders ?></h1>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-blue-100 text-blue-600"><i class="fas fa-indian-rupee-sign"></i></div>
        <h3 class="text-gray-500 text-lg">Total Earnings</h3>
        <h1 class="text-4xl font-bold text-green-800 mt-2">₹<?= number_format($totalEarnings,2) ?></h1>
      </div>
    </div>

    

    <div class="tips-section">
      <h2 class="text-3xl font-bold text-green-800 mb-4">Farming Tips & Insights</h2>
      <div class="tips-grid">
        <div class="tip-card">
          <div class="tip-icon"><i class="fas fa-hand-holding-water"></i></div>
          <h3 class="text-xl font-semibold mb-2">Crop Care Tip</h3>
          <p class="text-gray-600">Water your crops early in the morning for optimal growth and health.</p>
        </div>
        <div class="tip-card">
          <div class="tip-icon"><i class="fas fa-cloud-sun-rain"></i></div>
          <h3 class="text-xl font-semibold mb-2">Weather Reminder</h3>
          <p class="text-gray-600">Expect heavy rains tomorrow. Make sure your harvest and storage are secured.</p>
        </div>
        <div class="tip-card">
          <div class="tip-icon"><i class="fas fa-chart-line"></i></div>
          <h3 class="text-xl font-semibold mb-2">Sales Tip</h3>
          <p class="text-gray-600">Consider selling fresh produce to local restaurants for higher profit margins.</p>
        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>