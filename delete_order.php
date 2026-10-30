<!-- delete_order.php -->
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

// Check if order exists
$checkOrder = $conn->query("SELECT * FROM orders WHERE order_id = $orderId");

if ($checkOrder->num_rows == 0) {
    die("Order not found");
}

// Delete order
$conn->query("DELETE FROM orders WHERE order_id = $orderId");

// Redirect back
header("Location: orders.php");
exit();
?>