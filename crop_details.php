<?php
session_start();
$conn = new mysqli("localhost","root","","cropsetu");

$id = $_GET['id'];

$res = $conn->query("
SELECT crops.*, users.name, users.phone, users.location 
FROM crops 
JOIN users ON crops.farmer_id = users.id
WHERE crop_id='$id'
");

$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<style>
body{
    margin:0;
    background:url('images/design.jpg') no-repeat center center fixed;
    background-size:cover;
}

/* Overlay removed black effect, just center content */
.overlay{
    /* background:rgba(0,0,0,0.2); */ /* Removed */
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.box{
    background:white;
    padding:20px;
    border-radius:15px;
    width:300px;
    text-align:center;
    box-shadow:0 4px 15px rgba(0,0,0,0.1); /* optional: adds soft shadow */
}

img{
    width:100%;
    height:150px;
    border-radius:10px;
}

input{
    width:90%;
    padding:8px;
    margin-top:10px;
}

button{
    background:green;
    color:white;
    padding:10px;
    border:none;
    margin-top:10px;
    cursor:pointer;
}
button:hover{
    background:#1b7a1b;
}
</style>

<script>
function calcTotal(price){
    let qty = document.getElementById("qty").value;
    document.getElementById("total").innerText = price * qty;
}
</script>

</head>

<body>

<div class="overlay">
<div class="box">

<img src="uploads/<?php echo $row['crop_image']; ?>">

<h3><?php echo $row['crop_name']; ?></h3>

<p><b>Price per kg:</b> ₹<?php echo $row['price']; ?></p>
<p><b>Available:</b> <?php echo $row['quantity']; ?> kg</p>

<p><b>Farmer:</b> <?php echo $row['name']; ?></p>
<p>phone number: <?php echo $row['phone']; ?></p>
<p>location: <?php echo $row['location']; ?></p>

<form action="place_order.php" method="POST">
<input type="hidden" name="crop_id" value="<?php echo $row['crop_id']; ?>">
<input type="hidden" name="farmer_id" value="<?php echo $row['farmer_id']; ?>">
<input type="hidden" name="price" value="<?php echo $row['price']; ?>">

<input type="number" id="qty" name="quantity"
max="<?php echo $row['quantity']; ?>"
placeholder="Enter quantity"
oninput="calcTotal(<?php echo $row['price']; ?>)" required>

<p><b>Total Price:</b> ₹<span id="total">0</span></p>

<button>Buy Now</button>
</form>

<br>

<a href="tel:<?php echo $row['phone']; ?>">📞 Call</a><br><br>

<a href="chat.php?user_id=<?php echo $row['farmer_id']; ?>">Message</a>

<br><br>
<a href="buy_crops.php">Back</a>

</div>
</div>

</body>
</html>