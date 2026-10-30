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
    header("Location: orders.php");
    exit();
}

$orderId = intval($_GET['id']);

// Fetch order details
$orderQuery = $conn->query("
    SELECT *
    FROM orders
    WHERE order_id = $orderId
");

if ($orderQuery->num_rows == 0) {
    die("Order not found");
}

$order = $orderQuery->fetch_assoc();

// Update order
if (isset($_POST['update_order'])) {
    $buyer_id = $_POST['buyer_id'];
    $crop_id = $_POST['crop_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $updateQuery = "
        UPDATE orders 
        SET buyer_id = '$buyer_id',
            crop_id = '$crop_id',
            quantity = '$quantity',
            price = '$price',
            status = '$status'
        WHERE order_id = $orderId
    ";

    if ($conn->query($updateQuery)) {
        header("Location: orders.php");
        exit();
    } else {
        $error = "Failed to update order";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Order</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 50%, #86efac 100%);
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

<div class="form-card rounded-[32px] p-8 w-full max-w-4xl">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="uppercase tracking-[4px] text-green-600 text-sm font-semibold">Cropsetu Admin</p>
            <h1 class="text-4xl font-bold text-green-900 mt-2">Edit Order</h1>
        </div>

        <a href="orders.php" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-3 rounded-2xl font-semibold transition flex items-center gap-2">
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

            <!-- Buyer -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Buyer</label>
                <select 
                    name="buyer_id" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-400 outline-none"
                >
                    <?php
                    $buyers = $conn->query("SELECT id, name FROM users WHERE role='buyer'");
                    while($buyer = $buyers->fetch_assoc()):
                    ?>
                        <option value="<?= $buyer['id'] ?>" <?= $order['buyer_id'] == $buyer['id'] ? 'selected' : '' ?>>
                            <?= $buyer['name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Crop -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Crop</label>
                <select 
                    name="crop_id" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-400 outline-none"
                >
                    <?php
                    $crops = $conn->query("SELECT crop_id, crop_name FROM crops");
                    while($crop = $crops->fetch_assoc()):
                    ?>
                        <option value="<?= $crop['crop_id'] ?>" <?= $order['crop_id'] == $crop['crop_id'] ? 'selected' : '' ?>>
                            <?= $crop['crop_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Quantity -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Quantity</label>
                <input 
                    type="number" 
                    name="quantity" 
                    value="<?= $order['quantity'] ?>" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-400 outline-none"
                >
            </div>

            <!-- Price -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Price</label>
                <input 
                    type="text" 
                    name="price" 
                    value="<?= $order['price'] ?>" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-400 outline-none"
                >
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-semibold mb-2">Status</label>
                <select 
                    name="status" 
                    required
                    class="input-field w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-green-400 outline-none"
                >
                    <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $order['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= $order['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button 
                type="submit" 
                name="update_order"
                class="w-full bg-gradient-to-r from-green-700 to-green-500 text-white py-4 rounded-2xl font-bold text-lg shadow-lg hover:scale-[1.02] transition duration-300 flex items-center justify-center gap-3"
            >
                <i class="fas fa-save"></i>
                Update Order
            </button>
        </div>

    </form>

</div>

</body>
</html>