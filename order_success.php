<?php
session_start();
include 'db.php';

$crop_id = $_GET['crop_id'];
$farmer_id = $_GET['farmer_id'];

/* Farmer details fetch */
$res = mysqli_query($conn,"SELECT name, phone FROM users WHERE id='$farmer_id'");
$farmer = mysqli_fetch_assoc($res);

/* Crop name fetch */
$crop_res = mysqli_query($conn,"SELECT crop_name FROM crops WHERE crop_id='$crop_id'");
$crop = mysqli_fetch_assoc($crop_res);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order Success</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/design.jpg') no-repeat center;
    background-size:cover;
}

.container{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    border-radius:15px;
    text-align:center;
    width:320px;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

h2{
    color:green;
}

button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:15px;
}

.call{background:green;color:white;}
.msg{background:#2196F3;color:white;}

.back{
    margin-top:15px;
}
</style>
</head>

<body>

<div class="container">

<div class="box">

<h2>Order Placed Successfully ✅</h2>

<p>You have ordered <b><?php echo $crop['crop_name']; ?></b></p>

<p>Please contact farmer to collect your crop</p>

<p><b>Farmer:</b> <?php echo $farmer['name']; ?></p>

<!-- CALL BUTTON -->
<button class="call" onclick="location.href='tel:<?php echo $farmer['phone']; ?>'">
📞 Call Farmer
</button>

<!-- MESSAGE BUTTON -->
<button class="msg" onclick="location.href='chat.php?user_id=<?php echo $farmer_id; ?>'">
💬 Message Farmer
</button>

<div class="back">
<a href="buyer_dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</div>

</body>
</html>