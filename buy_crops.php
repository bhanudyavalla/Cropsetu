<?php
session_start();
$conn = new mysqli("localhost","root","","cropsetu");

/* LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
    $search = $_GET['search'];
    $res = $conn->query("SELECT * FROM crops WHERE crop_name LIKE '%$search%'");
} else {
    $res = $conn->query("SELECT * FROM crops");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Buy Crops</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:url('images/design.jpg') no-repeat center center fixed;
    background-size:cover;
}

/* overlay */
.overlay{
    background:rgba(0,0,0,0.3);
    min-height:100vh;
    display:flex;
    flex-direction:column;
    align-items:center;
}

/* SEARCH BOX */
.search-box{
    margin-top:30px;
}

.search-box input{
    padding:10px;
    width:250px;
    border-radius:8px;
    border:none;
}

.search-box button{
    padding:10px;
    background:#2e7d32;
    color:white;
    border:none;
    border-radius:8px;
}

/* GRID CENTER */
.grid{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:20px;
    margin-top:30px;
}

/* CARD */
.card{
    width:180px;
    background:white;
    padding:10px;
    border-radius:12px;
    text-align:center;
    cursor:pointer;
}

.card img{
    width:100%;
    height:110px;
    border-radius:8px;
    object-fit:cover;
}

.card p{
    margin:5px 0;
}

/* BACK */
.back{
    margin:20px;
    color:white;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="overlay">

<!-- SEARCH -->
<form method="GET" class="search-box">
    <input type="text" name="search" placeholder="Search crops..." value="<?php echo $search; ?>">
    <button>Search</button>
</form>

<!-- CROPS -->
<div class="grid">

<?php while($row = $res->fetch_assoc()){ 

    /* IMAGE FIX */
    $img = "uploads/".$row['crop_image'];
    if(empty($row['crop_image']) || !file_exists($img)){
        $img = "uploads/default.png";
    }
?>

<div class="card" onclick="location.href='crop_details.php?id=<?php echo $row['crop_id']; ?>'">

<img src="<?php echo $img; ?>">

<p><b><?php echo $row['crop_name']; ?></b></p>
<p>₹<?php echo $row['price']; ?> / kg</p>
<p>Qty: <?php echo $row['quantity']; ?> kg</p>

</div>

<?php } ?>

</div>

<!-- BACK -->
<a href="buyer_dashboard.php" class="back">⬅ Back</a>

</div>

</body>
</html>