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
    header("Location: users.php");
    exit();
}

$userId = intval($_GET['id']);

// Fetch user details
$userQuery = $conn->query("
    SELECT *
    FROM users
    WHERE id = $userId
");

if ($userQuery->num_rows == 0) {
    die("User not found");
}

$userData = $userQuery->fetch_assoc();

// Update user
if (isset($_POST['update_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $updateQuery = "
        UPDATE users 
        SET name = '$name',
            email = '$email',
            role = '$role'
        WHERE id = $userId
    ";

    if ($conn->query($updateQuery)) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Failed to update user";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit User</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 50%, #93c5fd 100%);
}
.form-card {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}
.input-field {
    transition: all 0.3s ease;
}
.input-field:focus {
    transform: translateY(-2px);
}
</style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

<div class="form-card rounded-[32px] p-8 w-full max-w-3xl">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="uppercase tracking-[4px] text-blue-600 text-sm font-semibold">Cropsetu Admin</p>
            <h1 class="text-4xl font-bold text-blue-900 mt-2">Edit User</h1>
        </div>

        <a href="users.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-2xl font-semibold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <?php if(isset($error)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-6">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Full Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="<?= htmlspecialchars($userData['name']) ?>" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-400 outline-none"
                >
            </div>

            <!-- Email -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    value="<?= htmlspecialchars($userData['email']) ?>" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-400 outline-none"
                >
            </div>

            <!-- Role -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Role</label>
                <select 
                    name="role" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-400 outline-none"
                >
                    <option value="admin" <?= $userData['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="farmer" <?= $userData['role'] == 'farmer' ? 'selected' : '' ?>>Farmer</option>
                    <option value="buyer" <?= $userData['role'] == 'buyer' ? 'selected' : '' ?>>Buyer</option>
                </select>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button 
                type="submit" 
                name="update_user"
                class="w-full bg-gradient-to-r from-blue-700 to-blue-500 text-white py-4 rounded-2xl font-bold text-lg shadow-lg hover:scale-[1.02] transition duration-300 flex items-center justify-center gap-3"
            >
                <i class="fas fa-save"></i>
                Update User
            </button>
        </div>

    </form>

</div>

</body>
</html>