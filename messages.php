<?php
session_start();
require_once "db.php";

// Buyer login check
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'buyer'){
    header("Location: login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch messages for this buyer
$sql = "SELECT m.*, u.name AS sender_name 
        FROM messages m
        LEFT JOIN users u ON m.sender_id = u.id
        WHERE m.receiver_id = $buyer_id
        ORDER BY m.created_at DESC";

$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Messages - CropSetu</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:url('images/msg.jpg') no-repeat center top;
    background-size:auto;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}
.container{
    max-width:900px;
    margin:50px auto;
    padding:20px;
    text-align:center;
    flex:1;
}
h2{
    color:#000;
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
    color:#000;
}
.card .sender{
    font-weight:bold;
    font-size:14px;
    color:#2e7d32;
}
.card .time{
    font-size:12px;
    color:#555;
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
.no-messages {
    display:flex;
    justify-content:center;
    align-items:center;
    height:60vh;
}
.no-messages p{
    color:#000;
    font-size:18px;
    font-weight:bold;
}
</style>
</head>
<body>

<div class="container">
<h2>Messages</h2>

<?php if($res->num_rows > 0){ ?>
<div class="grid">
<?php while($row = $res->fetch_assoc()){ ?>
<div class="card">
    <p class="sender"><?php echo $row['sender_name']; ?></p>
    <p><?php echo $row['message']; ?></p>
    <p class="time"><?php echo $row['created_at']; ?></p>
</div>
<?php } ?>
</div>
<?php } else { ?>
<div class="no-messages">
    <p>No messages yet.</p>
</div>
<?php } ?>

<a href="buyer_dashboard.php" class="back-btn">⬅ Back</a>
</div>

</body>
</html>