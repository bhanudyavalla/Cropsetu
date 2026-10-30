<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$totalFarmers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='farmer'")->fetch_assoc()['total'] ?? 0;
$totalBuyers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='buyer'")->fetch_assoc()['total'] ?? 0;
$totalCrops = $conn->query("SELECT COUNT(*) AS total FROM crops")->fetch_assoc()['total'] ?? 0;
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0;

$newFarmersToday = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='farmer' AND DATE(created_at)=CURDATE()")
->fetch_assoc()['total'] ?? 0;

$newOrdersToday = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE DATE(created_at)=CURDATE()")
->fetch_assoc()['total'] ?? 0;

$newCropsToday = $conn->query("SELECT COUNT(*) AS total FROM crops WHERE DATE(created_at)=CURDATE()")
->fetch_assoc()['total'] ?? 0;

$orderLabels = [];
$weeklyOrders = [];
$tempOrders = [];

$orderQuery = $conn->query("
    SELECT DATE(created_at) AS order_day, COUNT(*) AS total_orders
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
");

while ($row = $orderQuery->fetch_assoc()) {
    $tempOrders[$row['order_day']] = $row['total_orders'];
}

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $orderLabels[] = date('D', strtotime($date));
    $weeklyOrders[] = $tempOrders[$date] ?? 0;
}

$popularCrops = [];
$popularCropQuery = $conn->query("
    SELECT c.crop_name, SUM(o.quantity) AS total_sold
    FROM orders o
    JOIN crops c ON o.crop_id = c.crop_id
    GROUP BY o.crop_id
    ORDER BY total_sold DESC
    LIMIT 4
");

while ($row = $popularCropQuery->fetch_assoc()) {
    $popularCrops[] = $row;
}

$activities = [];

$farmerActivity = $conn->query("SELECT name, created_at FROM users WHERE role='farmer' ORDER BY created_at DESC LIMIT 3");
while ($row = $farmerActivity->fetch_assoc()) {
    $activities[] = [
        'message' => $row['name'] . ' joined as a farmer',
        'time' => strtotime($row['created_at']),
        'type' => 'Farmer Joined',
        'icon' => 'fa-user-plus',
        'color' => 'green'
    ];
}

$cropActivity = $conn->query("SELECT u.name, c.crop_name, c.created_at FROM crops c JOIN users u ON c.farmer_id = u.id ORDER BY c.created_at DESC LIMIT 3");
while ($row = $cropActivity->fetch_assoc()) {
    $activities[] = [
        'message' => $row['name'] . ' added ' . $row['crop_name'],
        'time' => strtotime($row['created_at']),
        'type' => 'Crop Added',
        'icon' => 'fa-seedling',
        'color' => 'emerald'
    ];
}

$orderActivity = $conn->query("SELECT b.name AS buyer_name, c.crop_name, o.created_at FROM orders o JOIN users b ON o.buyer_id = b.id JOIN crops c ON o.crop_id = c.crop_id ORDER BY o.created_at DESC LIMIT 3");
while ($row = $orderActivity->fetch_assoc()) {
    $activities[] = [
        'message' => $row['buyer_name'] . ' ordered ' . $row['crop_name'],
        'time' => strtotime($row['created_at']),
        'type' => 'Order Placed',
        'icon' => 'fa-cart-shopping',
        'color' => 'orange'
    ];
}

usort($activities, fn($a, $b) => $b['time'] - $a['time']);
$activities = array_slice($activities, 0, 5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cropsetu Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #ecfdf5 0%, #dcfce7 45%, #f0fdf4 100%);
  }

  .grain-bg {
    background-image:
      radial-gradient(rgba(34,197,94,0.10) 1px, transparent 1px),
      linear-gradient(to right, rgba(255,255,255,0.15), rgba(255,255,255,0));
    background-size: 24px 24px;
  }

  .yard-card {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 30px;
    box-shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
    transition: all 0.35s ease;
    cursor: pointer;
  }

  .yard-card:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 25px 50px rgba(22, 101, 52, 0.18);
  }

  .hero-card {
    background: linear-gradient(135deg, #14532d 0%, #166534 40%, #22c55e 100%);
    border-radius: 38px;
    position: relative;
    overflow: hidden;
  }

  .hero-card::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
    top: -120px;
    right: -100px;
  }

  .search-box {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.6);
    border-radius: 24px;
    box-shadow: 0 8px 25px rgba(15,23,42,0.08);
  }

  .mini-stat {
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
  }

  .mini-stat:hover {
    background: rgba(255,255,255,0.18);
    transform: translateY(-5px);
  }
</style>
</head>
<body class="grain-bg min-h-screen text-slate-800">

<div class="flex min-h-screen">

    <aside class="w-24 bg-gradient-to-b from-[#14532d] to-[#166534] flex flex-col items-center py-8 gap-6 rounded-r-[40px] shadow-2xl">
        <div class="w-14 h-14 rounded-2xl bg-[#86efac] flex items-center justify-center text-2xl text-[#166534] font-bold">
            C
        </div>

        <div class="flex flex-col gap-5 mt-6 text-white text-xl">
            <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]" onclick="location.href='admin_dashboard.php'">
                <i class="fas fa-house"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='users.php'">
                <i class="fas fa-users"></i>
            </button> 

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='crops.php'">
                <i class="fas fa-seedling"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='adminorders.php'">
                <i class="fas fa-cart-shopping"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='analysis.php'">
                <i class="fas fa-chart-line"></i>
            </button>

             <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='logout.php'">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </aside>

    <main class="flex-1 p-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <p class="uppercase tracking-[4px] text-[#15803d] text-sm font-semibold">Cropsetu Admin</p>
                <h1 class="text-5xl font-bold mt-2 text-[#14532d]">Dashboard Overview</h1>
                <p class="text-slate-500 mt-3">Track users, crop listings, orders and marketplace growth.</p>
            </div>

            <div class="glass-card px-6 py-4 flex items-center gap-4 min-w-[300px]">
                <i class="fas fa-magnifying-glass text-slate-400"></i>
                <input type="text" placeholder="Search here..." class="bg-transparent outline-none w-full text-slate-700">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

            <div class="glass-card stat-card p-6 bg-gradient-to-br from-[#14532d] to-[#166534] text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[#bbf7d0] text-sm">Total Farmers</p>
                        <h2 class="text-4xl font-bold mt-2"><?= $totalFarmers ?></h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        <i class="fas fa-user-group"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card stat-card p-6 bg-gradient-to-br from-[#166534] to-[#22c55e] text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[#dcfce7] text-sm">Total Buyers</p>
                        <h2 class="text-4xl font-bold mt-2"><?= $totalBuyers ?></h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card stat-card p-6 bg-gradient-to-br from-[#15803d] to-[#4ade80] text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[#ecfdf5] text-sm">Crop Listings</p>
                        <h2 class="text-4xl font-bold mt-2"><?= $totalCrops ?></h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        <i class="fas fa-seedling"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card stat-card p-6 bg-gradient-to-br from-[#166534] to-[#84cc16] text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-[#f0fdf4] text-sm">Total Orders</p>
                        <h2 class="text-4xl font-bold mt-2"><?= $totalOrders ?></h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl">
                        <i class="fas fa-cart-shopping"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

            <div class="xl:col-span-2 glass-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-[#14532d]">Weekly Orders</h3>
                        <p class="text-slate-500">Orders received during the last 7 days</p>
                    </div>
                    <div class="bg-[#dcfce7] text-[#166534] px-4 py-2 rounded-xl font-semibold">
                        Last 7 Days
                    </div>
                </div>
                <canvas id="ordersChart" height="110"></canvas>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-2xl font-bold text-[#14532d] mb-6">Today's Activity</h3>

                <div class="space-y-4">
                    <div class="bg-[#f0fdf4] rounded-2xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-slate-500 text-sm">New Farmers</p>
                            <h4 class="text-3xl font-bold text-[#14532d] mt-1"><?= $newFarmersToday ?></h4>
                        </div>
                        <i class="fas fa-user-plus text-3xl text-green-600"></i>
                    </div>

                    <div class="bg-[#f0fdf4] rounded-2xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-slate-500 text-sm">New Orders</p>
                            <h4 class="text-3xl font-bold text-[#14532d] mt-1"><?= $newOrdersToday ?></h4>
                        </div>
                        <i class="fas fa-cart-plus text-3xl text-green-600"></i>
                    </div>

                    <div class="bg-[#f0fdf4] rounded-2xl p-4 flex justify-between items-center">
                        <div>
                            <p class="text-slate-500 text-sm">New Crops Added</p>
                            <h4 class="text-3xl font-bold text-[#14532d] mt-1"><?= $newCropsToday ?></h4>
                        </div>
                        <i class="fas fa-seedling text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

            <div class="glass-card p-6">
                <h3 class="text-2xl font-bold text-[#14532d] mb-6">Top Selling Crops</h3>

                <div class="space-y-5">
                    <?php foreach($popularCrops as $crop): ?>
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-slate-700"><?= htmlspecialchars($crop['crop_name']) ?></span>
                            <span class="font-bold text-[#166534]"><?= $crop['total_sold'] ?> kg</span>
                        </div>
                        <div class="w-full h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-[#166534] to-[#4ade80]" style="width: <?= min($crop['total_sold'], 100) ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-[#14532d]">Recent Activity</h3>
                    <button onclick="location.href='activity.php'" class="bg-[#166534] text-white px-5 py-2 rounded-xl hover:bg-[#14532d] transition">
                        View All
                    </button>
                </div>

                <div class="space-y-4">
                    <?php foreach($activities as $act): ?>
                    <div class="bg-[#f8fafc] rounded-2xl p-4 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-[#dcfce7] text-[#166534] flex items-center justify-center text-xl">
                            <i class="fas <?= $act['icon'] ?>"></i>
                        </div>

                        <div class="flex-1">
                            <h4 class="font-bold text-slate-800"><?= htmlspecialchars($act['message']) ?></h4>
                            <p class="text-sm text-slate-500 mt-1"><?= date('d M Y, h:i A', $act['time']) ?></p>
                        </div>

                        <span class="bg-[#dcfce7] text-[#166534] px-3 py-2 rounded-full text-xs font-semibold">
                            <?= $act['type'] ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
const ctx = document.getElementById('ordersChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($orderLabels) ?>,
        datasets: [{
            label: 'Orders',
            data: <?= json_encode($weeklyOrders) ?>,
            borderColor: '#166534',
            backgroundColor: 'rgba(34,197,94,0.15)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#166534',
            pointRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#e2e8f0'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>

</body>
</html>