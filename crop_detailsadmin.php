<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Crop ID not found");
}

$crop_id = (int)$_GET['id'];

// Crop + Farmer Details
$query = $conn->query("
    SELECT 
        c.crop_id,
        c.crop_name,
        c.price,
        c.quantity,
        c.crop_image,
        c.created_at AS crop_added_date,
        u.name AS farmer_name,
        u.phone,
        u.location,
        u.created_at AS farmer_joined_date
    FROM crops c
    JOIN users u ON c.farmer_id = u.id
    WHERE c.crop_id = $crop_id
");

if ($query->num_rows == 0) {
    die("Crop not found");
}

$crop = $query->fetch_assoc();

// Orders for this crop
$orders = [];
$totalOrdered = 0;

$orderQuery = $conn->query("
    SELECT 
        o.quantity,
        o.status,
        o.created_at,
        b.name AS buyer_name
    FROM orders o
    JOIN users b ON o.buyer_id = b.id
    WHERE o.crop_id = $crop_id
    ORDER BY o.created_at DESC
");

while ($row = $orderQuery->fetch_assoc()) {
    $orders[] = $row;
    $totalOrdered += $row['quantity'];
}

$remainingQuantity = $crop['quantity'] - $totalOrdered;
if ($remainingQuantity < 0) {
    $remainingQuantity = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($crop['crop_name']) ?> Details</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ecfdf5 0%, #dcfce7 50%, #bbf7d0 100%);
        }

        .grain-bg {
            background-image: radial-gradient(rgba(34,197,94,0.12) 1px, transparent 1px);
            background-size: 22px 22px;
        }

        .glass-card {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.5);
            border-radius: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .crop-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 24px;
        }

        .info-box {
            background: rgba(255,255,255,0.7);
            border-radius: 20px;
            padding: 18px;
        }
    </style>
</head>
<body class="grain-bg min-h-screen text-slate-800">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-24 bg-gradient-to-b from-[#14532d] to-[#166534] flex flex-col items-center py-8 gap-6 rounded-r-[40px] shadow-2xl">
        <div class="w-14 h-14 rounded-2xl bg-[#86efac] flex items-center justify-center text-2xl text-[#166534] font-bold">
            C
        </div>

        <div class="flex flex-col gap-5 mt-6 text-white text-xl">
            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='dashboard.php'">
                <i class="fas fa-house"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='users.php'">
                <i class="fas fa-users"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]" onclick="location.href='crops.php'">
                <i class="fas fa-seedling"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='orders.php'">
                <i class="fas fa-cart-shopping"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='analysis.php'">
                <i class="fas fa-chart-line"></i>
            </button>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 p-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-[#14532d]"><?= htmlspecialchars($crop['crop_name']) ?> Details</h1>
                <p class="text-slate-600 mt-2">Full crop information and buyer activity</p>
            </div>

            <button onclick="window.location.href='crops.php'" class="bg-[#166534] text-white px-6 py-3 rounded-2xl shadow-lg hover:bg-[#14532d] transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Crops
            </button>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- Left Side -->
            <div class="xl:col-span-2 space-y-8">

                <div class="glass-card p-6">
                    <?php if(!empty($crop['crop_image'])): ?>
                        <img src="uploads/<?= $crop['crop_image'] ?>" class="crop-image mb-6">
                    <?php else: ?>
                        <div class="crop-image mb-6 bg-slate-200 flex items-center justify-center text-7xl text-slate-400">
                            <i class="fas fa-seedling"></i>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Price</p>
                            <h2 class="text-2xl font-bold text-[#14532d]">₹<?= number_format($crop['price'],2) ?>/kg</h2>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Total Quantity</p>
                            <h2 class="text-2xl font-bold text-[#14532d]"><?= $crop['quantity'] ?> kg</h2>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Ordered Quantity</p>
                            <h2 class="text-2xl font-bold text-orange-500"><?= $totalOrdered ?> kg</h2>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Remaining Quantity</p>
                            <h2 class="text-2xl font-bold text-green-600"><?= $remainingQuantity ?> kg</h2>
                        </div>
                    </div>
                </div>

                <!-- Orders -->
                <div class="glass-card p-6">
                    <h2 class="text-2xl font-bold text-[#14532d] mb-5">Buyer Orders</h2>

                    <?php if(count($orders) > 0): ?>
                        <div class="space-y-4">
                            <?php foreach($orders as $order): ?>
                                <div class="bg-white/70 rounded-2xl p-4 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-800">
                                            <?= htmlspecialchars($order['buyer_name']) ?>
                                        </h3>
                                        <p class="text-slate-600">
                                            Ordered <?= $order['quantity'] ?> kg
                                        </p>
                                        <p class="text-slate-500 text-sm">
                                            <?= date('d M Y', strtotime($order['created_at'])) ?>
                                        </p>
                                    </div>

                                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                                        <?php
                                            if($order['status'] == 'Completed') echo 'bg-green-100 text-green-700';
                                            elseif($order['status'] == 'Pending') echo 'bg-yellow-100 text-yellow-700';
                                            elseif($order['status'] == 'Cancelled') echo 'bg-red-100 text-red-700';
                                            else echo 'bg-orange-100 text-orange-700';
                                        ?>">
                                        <?= $order['status'] ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-100 rounded-2xl p-6 text-center text-slate-500">
                            No buyers have ordered this crop yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side -->
            <div class="space-y-6">

                <div class="glass-card p-6">
                    <h2 class="text-2xl font-bold text-[#14532d] mb-5">Farmer Details</h2>

                    <div class="space-y-4">
                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Farmer Name</p>
                            <h3 class="text-lg font-bold"><?= htmlspecialchars($crop['farmer_name']) ?></h3>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Mobile Number</p>
                            <h3 class="text-lg font-bold"><?= htmlspecialchars($crop['phone']) ?></h3>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Location</p>
                            <h3 class="text-lg font-bold"><?= htmlspecialchars($crop['location']) ?></h3>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Farmer Joined</p>
                            <h3 class="text-lg font-bold"><?= date('d M Y', strtotime($crop['farmer_joined_date'])) ?></h3>
                        </div>

                        <div class="info-box">
                            <p class="text-slate-500 text-sm">Crop Added On</p>
                            <h3 class="text-lg font-bold"><?= date('d M Y', strtotime($crop['crop_added_date'])) ?></h3>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>
</div>

</body>
</html>