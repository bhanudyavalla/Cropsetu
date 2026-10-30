<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch latest 20 activities (farmers joining, crops added, orders placed)
$activities = [];

// Farmer joins
$farmerActivity = $conn->query("
    SELECT name, created_at
    FROM users
    WHERE role='farmer'
    ORDER BY created_at DESC
    LIMIT 20
");
while($row = $farmerActivity->fetch_assoc()){
    $activities[] = [
        'message' => $row['name'] . ' joined as a farmer',
        'time' => strtotime($row['created_at']),
        'type' => 'Farmer Joined'
    ];
}

// Crops added
$cropActivity = $conn->query("
    SELECT u.name, c.crop_name, c.created_at
    FROM crops c
    JOIN users u ON c.farmer_id = u.id
    ORDER BY c.created_at DESC
    LIMIT 20
");
while($row = $cropActivity->fetch_assoc()){
    $activities[] = [
        'message' => $row['name'] . ' added ' . $row['crop_name'],
        'time' => strtotime($row['created_at']),
        'type' => 'Crop Added'
    ];
}

// Orders placed
$orderActivity = $conn->query("
    SELECT b.name AS buyer_name, c.crop_name, o.created_at
    FROM orders o
    JOIN users b ON o.buyer_id = b.id
    JOIN crops c ON o.crop_id = c.crop_id
    ORDER BY o.created_at DESC
    LIMIT 20
");
while($row = $orderActivity->fetch_assoc()){
    $activities[] = [
        'message' => $row['buyer_name'] . ' ordered ' . $row['crop_name'],
        'time' => strtotime($row['created_at']),
        'type' => 'Order Placed'
    ];
}

// Sort activities by latest first
usort($activities, function($a, $b){
    return $b['time'] - $a['time'];
});

// Limit to 20 latest activities
$activities = array_slice($activities, 0, 20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marketplace Activity - Cropsetu Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { font-family:'Poppins', sans-serif; background: linear-gradient(135deg,#ecfdf5 0%,#dcfce7 50%,#bbf7d0 100%); }
.yard-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border-radius:28px; padding:1rem; margin-bottom:1rem; }
</style>
</head>
<body class="min-h-screen text-slate-800 p-8">

<div class="flex justify-between items-center mb-6">
    <h1 class="text-4xl font-bold text-[#14532d]">Marketplace Activity</h1>
    <button onclick="location.href='admin_dashboard.php'" class="bg-green-600 text-white px-5 py-3 rounded-2xl shadow-lg flex items-center gap-2 hover:bg-green-700">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </button>
</div>

<div class="space-y-4">
    <?php if(!empty($activities)): ?>
        <?php foreach($activities as $activity): ?>
        <div class="yard-card flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center text-xl">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg"><?= $activity['message'] ?></h4>
                    <p class="text-slate-500 text-sm"><?= date('d M Y, h:i A', $activity['time']) ?></p>
                </div>
            </div>
            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm"><?= $activity['type'] ?></span>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-slate-500">No recent activities found.</p>
    <?php endif; ?>
</div>

</body>
</html>