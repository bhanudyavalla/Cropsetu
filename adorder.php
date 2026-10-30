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

// Delete Order
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE order_id = $deleteId");
    header("Location: orders.php");
    exit();
}

// Fetch orders
$orders = [];
$orderQuery = $conn->query("
    SELECT 
        o.id,
        IFNULL(b.name,'Unknown Buyer') AS buyer_name,
        IFNULL(f.name,'Unknown Farmer') AS farmer_name,
        IFNULL(c.crop_name,'Unknown Crop') AS crop_name,
        o.quantity,
        IFNULL(o.price,'-') AS price,
        o.status,
        o.created_at
    FROM orders o
    LEFT JOIN users b ON o.buyer_id = b.id
    LEFT JOIN crops c ON o.crop_id = c.crop_id
    LEFT JOIN users f ON c.farmer_id = f.id
    ORDER BY o.created_at DESC
");
while($row = $orderQuery->fetch_assoc()){
    $orders[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cropsetu Admin - Orders</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { 
    font-family: 'Poppins', sans-serif; 
    background: linear-gradient(135deg,#ecfdf5 0%,#dcfce7 50%,#bbf7d0 100%); 
}
.grain-bg { 
    background-image: radial-gradient(rgba(34,197,94,0.12) 1px, transparent 1px); 
    background-size:22px 22px; 
}
.yard-card { 
    background: rgba(255,255,255,0.7); 
    backdrop-filter: blur(12px); 
    border:1px solid rgba(255,255,255,0.4); 
    border-radius:28px; 
    box-shadow:0 10px 30px rgba(0,0,0,0.08); 
    transition:all 0.3s ease; 
}
.yard-card:hover { 
    transform:translateY(-6px); 
    box-shadow:0 18px 40px rgba(0,0,0,0.12); 
}
.action-btn {
    transition: all 0.3s ease;
}
.action-btn:hover {
    transform: scale(1.08);
}
</style>
</head>
<body class="grain-bg min-h-screen text-slate-800">

<div class="flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-24 bg-gradient-to-b from-[#14532d] to-[#166534] flex flex-col items-center py-8 gap-6 rounded-r-[40px] shadow-2xl">
    <div class="w-14 h-14 rounded-2xl bg-[#86efac] flex items-center justify-center text-2xl text-[#166534] font-bold">C</div>
    <div class="flex flex-col gap-5 mt-6 text-white text-xl">
      <button class="w-14 h-14 rounded-2xl bg-white/10 hover:bg-white/20 transition" onclick="location.href='admin_dashboard.php'"><i class="fas fa-house"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10 hover:bg-white/20 transition" onclick="location.href='users.php'"><i class="fas fa-users"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10 hover:bg-white/20 transition" onclick="location.href='crops.php'"><i class="fas fa-seedling"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534] shadow-lg"><i class="fas fa-cart-shopping"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10 hover:bg-white/20 transition" onclick="location.href='analysis.php'"><i class="fas fa-chart-line"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='logout.php'">
                <i class="fas fa-minus"></i>
            </button>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-8">
    <div class="flex justify-between items-center mb-8">
      <div>
        <p class="uppercase tracking-[4px] text-[#15803d] text-sm font-semibold">Cropsetu Admin</p>
        <h1 class="text-4xl font-bold mt-2 text-[#14532d]">Orders</h1>
      </div>
      <button onclick="location.href='admin_dashboard.php'" class="bg-gradient-to-r from-[#166534] to-[#22c55e] text-white px-5 py-3 rounded-2xl shadow-lg hover:scale-105 transition">
        Back to Dashboard
      </button>
    </div>

    <div class="yard-card p-6 overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-green-200 text-green-900">
            <th class="p-3 rounded-l-2xl">Buyer</th>
            <th class="p-3">Farmer</th>
            <th class="p-3">Crop</th>
            <th class="p-3">Quantity</th>
            <th class="p-3">Price</th>
            <th class="p-3">Status</th>
            <th class="p-3">Date</th>
            <th class="p-3 rounded-r-2xl text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if(count($orders) > 0): ?>
            <?php foreach($orders as $order): ?>
            <tr class="border-b hover:bg-green-50 transition">
              <td class="p-3"><?= $order['buyer_name'] ?></td>
              <td class="p-3"><?= $order['farmer_name'] ?></td>
              <td class="p-3"><?= $order['crop_name'] ?></td>
              <td class="p-3"><?= $order['quantity'] ?></td>
              <td class="p-3"><?= $order['price'] ?></td>
              <td class="p-3">
                <span class="px-3 py-1 rounded-full text-sm font-semibold 
                  <?= $order['status'] == 'Pending' ? 'bg-yellow-100 text-yellow-700' : '' ?>
                  <?= $order['status'] == 'Completed' ? 'bg-green-100 text-green-700' : '' ?>
                  <?= $order['status'] == 'Cancelled' ? 'bg-red-100 text-red-700' : '' ?>">
                  <?= $order['status'] ?>
                </span>
              </td>
              <td class="p-3"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></td>
              <td class="p-3">
                <div class="flex items-center justify-center gap-3">
                  
                  <!-- Edit Button -->
                  <a href="edit_order.php?id=<?= $order['id'] ?>" 
                     class="action-btn bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl shadow-md flex items-center gap-2">
                    <i class="fas fa-pen"></i>
                    Edit
                  </a>

                  <!-- Delete Button -->
                  <a href="orders.php?delete=<?= $order['id'] ?>" 
                     onclick="return confirm('Are you sure you want to delete this order?')"
                     class="action-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl shadow-md flex items-center gap-2">
                    <i class="fas fa-trash"></i>
                    Delete
                  </a>

                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="p-6 text-center text-slate-500 text-lg">
                No orders found
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

</body>
</html>