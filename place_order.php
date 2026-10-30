<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$buyer_id = $_SESSION['user_id'];

$crop_id   = $_POST['crop_id'];
$farmer_id = $_POST['farmer_id'];
$quantity  = $_POST['quantity'];
$price     = $_POST['price'];

$total = $quantity * $price;

/* 🔥 INSERT ORDER */
mysqli_query($conn,"
INSERT INTO orders (buyer_id, farmer_id, crop_id, quantity,price, status)
VALUES ('$buyer_id','$farmer_id','$crop_id','$quantity','$total','pending')
");

/* 🔥 STOCK REDUCE */
mysqli_query($conn,"
UPDATE crops SET quantity = quantity - $quantity 
WHERE crop_id='$crop_id'
");

/* 🔥 GET BUYER NAME */
$buyer_res = mysqli_query($conn,"SELECT name FROM users WHERE id='$buyer_id'");
$buyer = mysqli_fetch_assoc($buyer_res);

/* 🔥 GET CROP NAME */
$crop_res = mysqli_query($conn,"SELECT crop_name FROM crops WHERE crop_id='$crop_id'");
$crop = mysqli_fetch_assoc($crop_res);

/* 🔥 NOTIFICATION MESSAGE */
$msg = $buyer['name']." wants to buy ".$crop['crop_name'];

/* 🔥 INSERT NOTIFICATION FOR FARMER */
mysqli_query($conn,"
INSERT INTO notifications(user_id,message)
VALUES('$farmer_id','$msg')
");

/* 🔥 REDIRECT */
header("Location: order_success.php?crop_id=$crop_id&farmer_id=$farmer_id");
exit;
?>