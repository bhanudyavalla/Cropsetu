<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$farmer_id = $_SESSION['user_id'];

/* 🔥 JOIN to get buyer + crop details */
$res = mysqli_query($conn,"
SELECT orders.*, users.name AS buyer_name, crops.crop_name 
FROM orders
JOIN users ON orders.buyer_id = users.id
JOIN crops ON orders.crop_id = crops.crop_id
WHERE orders.farmer_id='$farmer_id'
ORDER BY orders.order_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/order.jpg') no-repeat center;
    background-size:cover;
}

.container{
    padding:30px;
}

.grid{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
}

.card{
    width:280px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    text-align:center;
}

.status{
    margin-top:10px;
    font-weight:bold;
}

.pending{color:orange;}
.accepted{color:green;}
.rejected{color:red;}

button{
    margin-top:10px;
    padding:8px 15px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.accept{background:green;color:white;}
.reject{background:red;color:white;}

.back{
    text-align:center;
    margin:20px;
}
</style>
</head>

<body>

<div class="container">

<h2 style="text-align:center;">My Orders</h2>

<?php if(mysqli_num_rows($res)>0){ ?>

<div class="grid">

<?php while($row=mysqli_fetch_assoc($res)){ ?>

<div class="card">

<h3><?php echo $row['crop_name']; ?></h3>

<p><b>Buyer:</b> <?php echo $row['buyer_name']; ?></p>
<p><b>Qty:</b> <?php echo $row['quantity']; ?> kg</p>
<p><b>Total:</b> ₹<?php echo $row['total_price']; ?></p>

<p class="status <?php echo $row['status']; ?>">
Status: <?php echo $row['status']; ?>
</p>

<?php if($row['status']=="pending"){ ?>

<form method="POST" action="update_order.php">
<input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">

<button name="accept" class="accept">Accept</button>
<button name="reject" class="reject">Reject</button>
</form>

<?php } ?>

</div>

<?php } ?>

</div>

<?php } else { ?>

<h3 style="text-align:center;color:black;">
You have no orders yet
</h3>

<?php } ?>

<div class="back">
<a href="farmer_dashboard.php">⬅ Back</a>
</div>

</div>

</body>
</html>