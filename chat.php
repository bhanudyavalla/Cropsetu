<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['user_id'];

/* 🔥 GET USER ROLE */
$user_res = mysqli_query($conn,"SELECT role FROM users WHERE id='$sender_id'");
$user = mysqli_fetch_assoc($user_res);
$role = $user['role'];

/* 🔥 BACK LINK BASED ON ROLE */
if($role == 'farmer'){
    $back = "chat_list.php"; // farmer side messages
} else {
    $back = "chat_list.php"; // buyer side messages
}

/* 🔥 GET MESSAGES */
$msgs = mysqli_query($conn,"
SELECT * FROM messages 
WHERE (sender_id='$sender_id' AND receiver_id='$receiver_id')
   OR (sender_id='$receiver_id' AND receiver_id='$sender_id')
ORDER BY created_at ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chat</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f0f0f0;
}

.container{
    max-width:500px;
    margin:auto;
    height:100vh;
    display:flex;
    flex-direction:column;
}

/* 🔥 BACK BAR */
.top{
    background:white;
    padding:10px;
    border-bottom:1px solid #ddd;
}

.top a{
    text-decoration:none;
    color:black;
    font-weight:bold;
}

.chat-box{
    flex:1;
    padding:15px;
    overflow-y:auto;
}

.msg{
    padding:10px;
    margin:5px 0;
    border-radius:10px;
    max-width:70%;
}

.sent{
    background:#4CAF50;
    color:white;
    margin-left:auto;
}

.received{
    background:white;
}

form{
    display:flex;
    padding:10px;
    background:white;
}

input{
    flex:1;
    padding:10px;
}

button{
    padding:10px;
    background:green;
    color:white;
    border:none;
}
</style>
</head>

<body>

<div class="container">

<!-- 🔥 BACK BUTTON -->
<div class="top">
    <a href="<?php echo $back; ?>">⬅ Back</a>
</div>

<div class="chat-box">

<?php while($m=mysqli_fetch_assoc($msgs)){ ?>

<div class="msg <?php echo ($m['sender_id']==$sender_id) ? 'sent' : 'received'; ?>">
<?php echo $m['message']; ?>
</div>

<?php } ?>

</div>

<form method="POST" action="send_message.php">
<input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
<input type="text" name="message" placeholder="Type message..." required>
<button>Send</button>
</form>

</div>

</body>
</html>