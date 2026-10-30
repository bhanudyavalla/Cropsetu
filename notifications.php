<?php
session_start();
include 'db.php';

/* 🔥 DEBUG CHECK */
if(!isset($_SESSION['user_id'])){
    echo "<h2 style='color:red;text-align:center;'>Session Problem ❌</h2>";
    echo "<p style='text-align:center;'>Please login again</p>";
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
    text-align:center;
}

.card{
    background:white;
    margin:10px auto;
    padding:15px;
    width:320px;
    border-radius:10px;
}

.back{
    margin-top:20px;
}
</style>
</head>

<body>

<div class="container">

<h2>Notifications</h2>

<?php if(mysqli_num_rows($res)>0){ ?>

<?php while($row=mysqli_fetch_assoc($res)){ ?>

<div class="card">
<p><?php echo $row['message']; ?></p>
<small><?php echo $row['created_at']; ?></small>
</div>

<?php } ?>

<?php } else { ?>

<h3>No notifications</h3>

<?php } ?>

<div class="back">
<button onclick="location.href='farmer_dashboard.php'">⬅ Back</button>
</div>

</div>

</body>
</html>