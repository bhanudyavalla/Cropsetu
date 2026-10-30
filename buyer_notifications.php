<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$res = mysqli_query($conn,"
SELECT * FROM notifications 
WHERE user_id='$user_id'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/design.jpg') no-repeat center;
    background-size:cover;
}

.container{
    padding:30px;
}

.card{
    background:white;
    margin:10px auto;
    padding:15px;
    width:300px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

.time{
    font-size:12px;
    color:gray;
}

.back{
    text-align:center;
    margin-top:20px;
}
</style>
</head>

<body>

<div class="container">

<h2 style="text-align:center;">Notifications</h2>

<?php if(mysqli_num_rows($res)>0){ ?>

<?php while($row=mysqli_fetch_assoc($res)){ ?>

<div class="card">
<p><?php echo $row['message']; ?></p>
<div class="time"><?php echo $row['created_at']; ?></div>
</div>

<?php } ?>

<?php } else { ?>

<h3 style="text-align:center;color:black;">
No notifications yet
</h3>

<?php } ?>

<div class="back">
<a href="buyer_dashboard.php">⬅ Back</a>
</div>

</div>

</body>
</html>