<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// --- Top Summary ---
$totalFarmers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='farmer'")->fetch_assoc()['total'] ?? 0;
$totalBuyers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='buyer'")->fetch_assoc()['total'] ?? 0;
$totalCrops = $conn->query("SELECT COUNT(*) AS total FROM crops")->fetch_assoc()['total'] ?? 0;
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0;
$totalRevenue = $conn->query("SELECT SUM(o.quantity*o.price) AS revenue FROM orders o")->fetch_assoc()['revenue'] ?? 0;

// --- Popular Crops ---
$popularCrops = [];
$popCropsQuery = $conn->query("
    SELECT c.crop_name, SUM(o.quantity) AS total_sold, SUM(o.quantity*o.price) AS revenue 
    FROM orders o 
    JOIN crops c ON o.crop_id = c.crop_id 
    GROUP BY o.crop_id 
    ORDER BY total_sold DESC 
    LIMIT 5
");
while($row = $popCropsQuery->fetch_assoc()){ $popularCrops[] = $row; }

// --- Top 5 Buyers ---
$topBuyers = [];
$topBuyersQuery = $conn->query("
    SELECT b.name, COUNT(*) AS orders_count, SUM(o.quantity*o.price) AS spent 
    FROM orders o 
    JOIN users b ON o.buyer_id = b.id 
    GROUP BY b.id 
    ORDER BY orders_count DESC 
    LIMIT 5
");
while($row = $topBuyersQuery->fetch_assoc()){ $topBuyers[] = $row; }

// --- Order Status Breakdown ---
$orderStatus = ['Pending'=>0,'Completed'=>0,'Partial'=>0,'Cancelled'=>0];
$statusQuery = $conn->query("SELECT o.status, COUNT(*) AS total FROM orders o GROUP BY o.status");
while($row = $statusQuery->fetch_assoc()){
    $orderStatus[$row['status']] = $row['total'];
}

// --- Revenue Trend (last 7 days) ---
$revenueLabels = [];
$revenueData = [];
$tempRevenue = [];
$revQuery = $conn->query("
    SELECT DATE(o.created_at) AS day, SUM(o.quantity*o.price) AS revenue 
    FROM orders o 
    WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
    GROUP BY DATE(o.created_at)
");
while($row = $revQuery->fetch_assoc()){ $tempRevenue[$row['day']] = $row['revenue']; }
for($i=6;$i>=0;$i--){
    $date = date('Y-m-d', strtotime("-$i days"));
    $revenueLabels[] = date('D', strtotime($date));
    $revenueData[] = $tempRevenue[$date] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cropsetu Analysis Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#ecfdf5 0%,#dcfce7 50%,#bbf7d0 100%);}
.yard-card{background:rgba(255,255,255,0.7);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.4);border-radius:28px;box-shadow:0 10px 30px rgba(0,0,0,0.08);transition:all 0.3s ease;cursor:pointer;}
.yard-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,0.12);}
.grain-bg{background-image:radial-gradient(rgba(34,197,94,0.12) 1px,transparent 1px);background-size:22px 22px;}
</style>
</head>
<body class="grain-bg min-h-screen text-slate-800">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="w-24 bg-gradient-to-b from-[#14532d] to-[#166534] flex flex-col items-center py-8 gap-6 rounded-r-[40px] shadow-2xl">
    <div class="w-14 h-14 rounded-2xl bg-[#86efac] flex items-center justify-center text-2xl text-[#166534] font-bold">C</div>
    <div class="flex flex-col gap-5 mt-6 text-white text-xl">
      <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]" onclick="location.href='admin_dashboard.php'"><i class="fas fa-house"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='users.php'"><i class="fas fa-users"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='crops.php'"><i class="fas fa-seedling"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='adminorders.php'"><i class="fas fa-cart-shopping"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]" onclick="location.href='analysis.php'"><i class="fas fa-chart-line"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='logout.php'">
                <i class="fas fa-minus"></i>
            </button>
    </div>
  </aside>

  <main class="flex-1 p-8">
    <h1 class="text-4xl font-bold text-[#14532d] mb-6">Marketplace Analysis</h1>

    <!-- Top Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
      <div class="yard-card p-4 text-center">Farmers<p class="text-2xl font-bold"><?= $totalFarmers ?></p></div>
      <div class="yard-card p-4 text-center">Buyers<p class="text-2xl font-bold"><?= $totalBuyers ?></p></div>
      <div class="yard-card p-4 text-center">Orders<p class="text-2xl font-bold"><?= $totalOrders ?></p></div>
      <div class="yard-card p-4 text-center">Crops<p class="text-2xl font-bold"><?= $totalCrops ?></p></div>
      <div class="yard-card p-4 text-center">Revenue<p class="text-2xl font-bold">₹<?= number_format($totalRevenue,2) ?></p></div>
    </div>

    <!-- Side by Side Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Order Status Pie -->
      <div class="yard-card p-6">
        <h2 class="text-2xl font-bold mb-4">Order Status</h2>
        <canvas id="statusChart"></canvas>
        <div class="flex justify-between mt-4">
          <span class="flex items-center gap-2"><span class="w-4 h-4 bg-yellow-400 block"></span> Pending</span>
          <span class="flex items-center gap-2"><span class="w-4 h-4 bg-green-500 block"></span> Completed</span>
          <span class="flex items-center gap-2"><span class="w-4 h-4 bg-orange-400 block"></span> Partial</span>
          <span class="flex items-center gap-2"><span class="w-4 h-4 bg-red-500 block"></span> Cancelled</span>
        </div>
      </div>

      <!-- Revenue Trend Line -->
      <div class="yard-card p-6">
        <h2 class="text-2xl font-bold mb-4">Revenue Trend (Last 7 Days)</h2>
        <canvas id="revenueChart"></canvas>
      </div>
    </div>

    <!-- Popular Crops -->
    <div class="yard-card p-6 mb-8">
      <h2 class="text-2xl font-bold mb-4">Top Selling Crops</h2>
      <div class="space-y-4">
        <?php foreach($popularCrops as $crop): ?>
          <div class="flex justify-between">
            <span><?= $crop['crop_name'] ?></span>
            <span><?= $crop['total_sold'] ?> kg / ₹<?= number_format($crop['revenue'],2) ?></span>
          </div>
          <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
            <div class="h-full bg-green-600 rounded-full" style="width: <?= min(100,$crop['total_sold']) ?>%"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Top Buyers -->
    <div class="yard-card p-6 mb-8">
      <h2 class="text-2xl font-bold mb-4">Top 5 Buyers</h2>
      <div class="space-y-2">
        <?php foreach($topBuyers as $buyer): ?>
          <div class="flex justify-between p-2 bg-white/10 rounded-xl">
            <span><?= $buyer['name'] ?></span>
            <span><?= $buyer['orders_count'] ?> Orders / ₹<?= number_format($buyer['spent'],2) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </main>
</div>

<script>
// Status Pie Chart
new Chart(document.getElementById('statusChart'), {
    type:'pie',
    data:{
        labels:['Pending','Completed','Partial','Cancelled'],
        datasets:[{data:[<?= implode(',', $orderStatus) ?>],
        backgroundColor:['#facc15','#22c55e','#f97316','#ef4444']}]
    },
    options:{plugins:{legend:{display:false}}}
});

// Revenue Trend Chart
new Chart(document.getElementById('revenueChart'), {
    type:'line',
    data:{
        labels:<?= json_encode($revenueLabels) ?>,
        datasets:[{label:'Revenue', data:<?= json_encode($revenueData) ?>, borderColor:'#166534', backgroundColor:'rgba(22,101,52,0.12)', fill:true, tension:0.4}]
    },
    options:{plugins:{legend:{display:false}}, scales:{y:{grid:{color:'#e2e8f0'}}, x:{grid:{display:false}}}}
});
</script>

</body>
</html>