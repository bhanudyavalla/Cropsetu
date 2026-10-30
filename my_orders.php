<?php
session_start();
require_once "db.php"; // db connection

// Buyer login check
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'buyer'){
    header("Location: login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch orders with crop and farmer info
$sql = "SELECT o.*, c.crop_name, f.name AS farmer_name 
        FROM orders o
        JOIN crops c ON o.crop_id = c.crop_id
        JOIN users f ON o.farmer_id = f.id
        WHERE o.buyer_id = $buyer_id
        ORDER BY o.order_date DESC";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders - CropSetu</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:url('images/design3.jpg') no-repeat center center fixed;
    background-size:cover;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

.container{
    max-width:900px;
    margin:50px auto;
    padding:20px;
    text-align:center;
    flex:1; /* allow flex centering */
}

h2{
    color:#000; /* black text */
    margin-bottom:20px;
}

.grid{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
}

.card{
    background:white;
    padding:15px;
    width:250px;
    border-radius:12px;
    text-align:left;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
}

.card p{
    margin:5px 0;
    color:#000; /* black text */
}

.back-btn{
    display:inline-block;
    padding:8px 20px;
    background:#555;
    color:white;
    border-radius:6px;
    text-decoration:none;
    margin-top:20px;
}

/* CENTER "no orders" MESSAGE */
.no-orders {
    display:flex;
    justify-content:center;
    align-items:center;
    height:60vh; /* middle of page */
}

.no-orders p{
    color:#000;
    font-size:18px;
    font-weight:bold;
}
</style>
</head>
<body>

<div class="container">
<h2>My Orders</h2>

<?php if($res->num_rows > 0){ ?>
<div class="grid">
<?php while($row = $res->fetch_assoc()){ ?>
<div class="card">
    <p><b>Farmer:</b> <?php echo $row['farmer_name']; ?></p>
    <p><b>Crop:</b> <?php echo $row['crop_name']; ?></p>
    <p><b>Quantity:</b> <?php echo $row['quantity']; ?> kg</p>
    <p><b>Total Price:</b> ₹<?php echo $row['price']; ?></p>
    <p><b>Status:</b> <?php echo $row['status']; ?></p>
    <p><b>Order Date:</b> <?php echo $row['order_date']; ?></p>
</div>
<?php } ?>
</div>
<?php } else { ?>
<div class="no-orders">
    <p>You haven’t placed any orders yet.</p>
</div>
<?php } ?>

<a href="buyer_dashboard.php" class="back-btn">⬅ Back</a>
</div>

</body>
</html>