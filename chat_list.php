<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* 🔥 GET ROLE */
$res_role = mysqli_query($conn,"SELECT role FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($res_role);

if($user['role'] == 'farmer'){
    $back_page = "farmer_dashboard.php";
} else {
    $back_page = "buyer_dashboard.php";
}

/* 🔥 GET CHAT USERS */
$res = mysqli_query($conn,"
SELECT DISTINCT 
    CASE 
        WHEN sender_id = '$user_id' THEN receiver_id
        ELSE sender_id
    END AS user_id
FROM messages
WHERE sender_id='$user_id' OR receiver_id='$user_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Messages</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/design.jpg') no-repeat center;
    background-size:cover;
}

.container{
    padding:20px;
}

.header{
    text-align:center;
    font-size:22px;
    margin-bottom:15px;
}

.chat{
    background:white;
    padding:15px;
    margin:10px auto;
    width:300px;
    border-radius:10px;
    cursor:pointer;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.chat:hover{
    background:#f1f1f1;
}

.back{
    text-align:center;
    margin-top:20px;
}

a{
    text-decoration:none;
    color:black;
}
</style>
</head>

<body>

<div class="container">

<div class="header">Messages</div>

<?php if(mysqli_num_rows($res)>0){ ?>

<?php while($row=mysqli_fetch_assoc($res)){ 

    $other_id = $row['user_id'];

    $user_res = mysqli_query($conn,"SELECT name FROM users WHERE id='$other_id'");
    $other = mysqli_fetch_assoc($user_res);
?>

<div class="chat" onclick="location.href='chat.php?user_id=<?php echo $other_id; ?>'">
<b><?php echo $other['name']; ?></b>
</div>

<?php } ?>

<?php } else { ?>

<h3 style="text-align:center;">No chats yet</h3>

<?php } ?>

<!-- 🔥 BACK BUTTON FIX -->
<div class="back">
<a href="<?php echo $back_page; ?>">⬅ Back</a>
</div>

</div>

</body>
</html>