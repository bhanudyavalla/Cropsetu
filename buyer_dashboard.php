<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Buyer Dashboard</title>

<!-- 🌿 GOOGLE ICONS -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Arial;
    height:100vh;
    background:url('images/idea2.jpg');
    background-size:100% 100%; /* full stretch */
    background-repeat:no-repeat;
    display:flex;
    flex-direction:column;
    align-items:center;
}

/* 🌿 TOP TITLE */
.title{
    margin-top:30px;
    font-size:28px;
    font-weight:bold;
    color:#2e7d32; /* green color */
    text-align:center;
}

/* 🌿 MAIN CENTER */
.main{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

/* 🌿 GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(2,180px);
    gap:20px;
    margin-bottom:30px;
}

/* 🌿 CARD */
.card{
    background:rgba(255,255,255,0.9);
    padding:25px;
    border-radius:12px;
    cursor:pointer;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-5px);
    background:#e8f5e9;
}

/* 🌿 ICON */
.material-symbols-outlined{
    font-size:32px;
    color:#2e7d32;
}

/* 🌿 TEXT */
.card span{
    display:block;
    margin-top:8px;
    font-size:14px;
    color:#333;
}

/* 🌿 QUOTE */
.quote{
    font-size:22px;
    color:#000;
    font-weight:bold;
    text-align:center;
    text-shadow:1px 1px 3px rgba(0,0,0,0.6);
}

/* 🌿 CONTENT */
.content{
    margin-top:10px;
    color:#000;
    width:60%;
    text-align:center;
}
</style>

</head>

<body>

<!-- 🌿 TITLE -->
<div class="title">Buyer Panel </div>

<!-- 🌿 MAIN -->
<div class="main">

<!-- 🌿 BUTTONS -->
<div class="grid">

<div class="card" onclick="location.href='profile.php'">
<span class="material-symbols-outlined">person</span>
<span>Profile</span>
</div>

<div class="card" onclick="location.href='logout.php'">
<span class="material-symbols-outlined">logout</span>
<span>Logout</span>
</div>

<div class="card" onclick="location.href='buy_crops.php'">
<span class="material-symbols-outlined">shopping_cart</span>
<span>Buy Crops</span>
</div>

<div class="card" onclick="location.href='my_orders.php'">
<span class="material-symbols-outlined">inventory_2</span>
<span>My Orders</span>
</div>

<div class="card" onclick="location.href='notifications.php'">
<span class="material-symbols-outlined">notifications</span>
<span>Notifications</span>
</div>

<div class="card" onclick="location.href='chat_list.php'">
<span class="material-symbols-outlined">chat</span>
<span>Messages</span>
</div>

</div>

<!-- 🔥 BACK BUTTON FIXED -->
<button onclick="location.href='login.php'" style="
position:absolute;
top:20px;
left:20px;
background:#2e7d32;
color:white;
padding:8px 15px;
border:none;">
← Back
</button>

<!-- 🌿 QUOTE -->
<div class="quote">
Buy fresh. Support farmers. Live healthy.
</div>

<!-- 🌿 CONTENT -->
<div class="content">
Explore fresh crops directly from farmers.  
Get quality produce at the best prices without middlemen.  
Your choices support farmers and healthy living.
</div>

</div>

</body>
</html>