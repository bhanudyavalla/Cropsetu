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

$crops = [];
$query = $conn->query("
    SELECT 
        c.crop_id,
        c.crop_name,
        c.price,
        c.quantity,
        c.crop_image,
        c.created_at,
        u.name AS farmer_name
    FROM crops c
    JOIN users u ON c.farmer_id = u.id
    ORDER BY c.created_at DESC
");

while ($row = $query->fetch_assoc()) {
    $crops[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crops - Admin Panel</title>

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
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.12);
        }

        .crop-image {
            height: 220px;
            width: 100%;
            object-fit: cover;
            border-radius: 22px;
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
            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='admin_dashboard.php'">
                <i class="fas fa-house"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='users.php'">
                <i class="fas fa-users"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]" onclick="location.href='crops.php'">
                <i class="fas fa-seedling"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='adminorders.php'">
                <i class="fas fa-cart-shopping"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='analysis.php'">
                <i class="fas fa-chart-line"></i>
            </button>

            <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='activity.php'">
                <i class="fas fa-clock-rotate-left"></i>
            </button>
<button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='logout.php'">
                <i class="fas fa-minus"></i>
            </button>

        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-[#14532d]">All Crops</h1>
                <p class="text-slate-600 mt-2">View all crops added by farmers</p>
            </div>

            <div class="bg-white/70 px-6 py-3 rounded-2xl shadow">
                <span class="text-slate-600">Total Crops:</span>
                <span class="font-bold text-[#14532d] text-xl"><?= count($crops) ?></span>
            </div>
        </div>

        <?php if(count($crops) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

                <?php foreach($crops as $crop): ?>
                    <a href="crop_detailsadmin.php?id=<?= $crop['crop_id'] ?>" class="block">
                        <div class="glass-card p-5 h-full cursor-pointer">

                            <?php if(!empty($crop['crop_image'])): ?>
                                <img src="uploads/<?= $crop['crop_image'] ?>" alt="<?= $crop['crop_name'] ?>" class="crop-image mb-5">
                            <?php else: ?>
                                <div class="crop-image mb-5 bg-slate-200 flex items-center justify-center text-slate-500 text-5xl">
                                    <i class="fas fa-seedling"></i>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-between items-start mb-3">
                                <h2 class="text-2xl font-bold text-[#14532d]">
                                    <?= htmlspecialchars($crop['crop_name']) ?>
                                </h2>

                                <span class="bg-[#dcfce7] text-[#166534] px-3 py-1 rounded-full text-sm font-semibold">
                                    ₹<?= number_format($crop['price'], 2) ?>/kg
                                </span>
                            </div>

                            <div class="space-y-3 text-slate-700">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-user text-[#166534]"></i>
                                    <span><?= htmlspecialchars($crop['farmer_name']) ?></span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <i class="fas fa-weight-hanging text-[#166534]"></i>
                                    <span><?= $crop['quantity'] ?> kg Available</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <i class="fas fa-calendar text-[#166534]"></i>
                                    <span><?= date('d M Y', strtotime($crop['created_at'])) ?></span>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-sm text-slate-500">
                                    Click to view details
                                </span>

                                <div class="w-10 h-10 rounded-xl bg-[#166534] text-white flex items-center justify-center">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>

                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <div class="glass-card p-10 text-center">
                <div class="text-6xl text-slate-300 mb-4">
                    <i class="fas fa-seedling"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-600 mb-2">No Crops Found</h2>
                <p class="text-slate-500">No farmer has added crops yet.</p>
            </div>
        <?php endif; ?>
    </main>
</div>

</body>
</html>