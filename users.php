<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch all users
$usersQuery = $conn->query("SELECT id, name, email, role, phone, created_at FROM users ORDER BY created_at DESC");
$users = [];
while ($row = $usersQuery->fetch_assoc()) $users[] = $row;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Users - Cropsetu Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body { font-family:'Poppins', sans-serif; background: linear-gradient(135deg,#ecfdf5 0%,#dcfce7 50%,#bbf7d0 100%); }
.yard-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(12px); border-radius:28px; transition:0.3s; cursor:pointer; }
.yard-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(0,0,0,0.12);}
.grain-bg { background-image: radial-gradient(rgba(34,197,94,0.12) 1px, transparent 1px); background-size:22px 22px;}
</style>
</head>
<body class="grain-bg min-h-screen text-slate-800">
<div class="flex min-h-screen">
  <aside class="w-24 bg-gradient-to-b from-[#14532d] to-[#166534] flex flex-col items-center py-8 gap-6 rounded-r-[40px] shadow-2xl">
    <div class="w-14 h-14 rounded-2xl bg-[#86efac] flex items-center justify-center text-2xl text-[#166534] font-bold">C</div>
    <div class="flex flex-col gap-5 mt-6 text-white text-xl">
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='admin_dashboard.php'"><i class="fas fa-house"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-[#86efac] text-[#166534]"><i class="fas fa-users"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='crops.php'"><i class="fas fa-seedling"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='adminorders.php'"><i class="fas fa-cart-shopping"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='analysis.php'"><i class="fas fa-chart-line"></i></button>
      <button class="w-14 h-14 rounded-2xl bg-white/10" onclick="location.href='logout.php'">
                <i class="fas fa-minus"></i>
            </button>
    </div>
  </aside>

  <main class="flex-1 p-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-4xl font-bold text-[#14532d]">Users</h1>
      <input type="text" id="searchUser" placeholder="Search by name or email..." class="px-4 py-2 rounded-xl border outline-none w-80">
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white/70 backdrop-blur-md rounded-2xl overflow-hidden shadow-lg">
        <thead class="bg-green-600 text-white">
          <tr>
            <th class="py-3 px-5 text-left">ID</th>
            <th class="py-3 px-5 text-left">Name</th>
            <th class="py-3 px-5 text-left">Email</th>
            <th class="py-3 px-5 text-left">Phone</th>
            <th class="py-3 px-5 text-left">Role</th>
            <th class="py-3 px-5 text-left">Joined On</th>
            <th class="py-3 px-5 text-left">Actions</th>
          </tr>
        </thead>
        <tbody id="userTable">
          <?php foreach($users as $user): ?>
          
  <tr class="border-b hover:bg-green-50 cursor-pointer">
    <td class="py-3 px-5"><?= $user['id'] ?></td>
    <td class="py-3 px-5"><?= $user['name'] ?></td>
    <td class="py-3 px-5"><?= $user['email'] ?></td>
    <td class="py-3 px-5"><?= $user['phone'] ?></td>
    <td class="py-3 px-5"><?= ucfirst($user['role']) ?></td>
    <td class="py-3 px-5"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
    <td class="py-3 px-5 flex gap-2">
      <button onclick="location.href='edit_user.php?id=<?= $user['id'] ?>'" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">
        <i class="fas fa-edit"></i> Edit
      </button>
      <button onclick="if(confirm('Are you sure you want to delete this user?')) location.href='delete_user.php?id=<?= $user['id'] ?>'" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
        <i class="fas fa-trash"></i> Delete
      </button>
    </td>
  </tr>
  <?php endforeach; ?>
</tbody>
      </table>
    </div>
  </main>
</div>



<script>
const searchInput = document.getElementById('searchUser');
searchInput.addEventListener('input', function() {
  const filter = this.value.toLowerCase();
  document.querySelectorAll('#userTable tr').forEach(row => {
    const name = row.cells[1].innerText.toLowerCase();
    const email = row.cells[2].innerText.toLowerCase();
    row.style.display = name.includes(filter) || email.includes(filter) ? '' : 'none';
  });
});
</script>
</body>
</html>