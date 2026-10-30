<?php
include("db.php");

// Fetch all crops
$res = mysqli_query($conn,"SELECT * FROM crops");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Crops</title>
<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/leaf2.jpg');
    background-size:cover;
}

/* HEADER */
.top{
    text-align:center;
    padding:20px;
}

.top h2{
    color:#2e7d32;
}

/* SEARCH */
.search{
    padding:10px 15px;
    width:260px;
    border-radius:25px;
    border:1px solid #ccc;
}

/* GRID FIX */
.grid{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:25px;
    padding:30px;
}

/* CARD */
.card{
    width:260px;
    background:#fff;
    border-radius:12px;
    padding:15px;
    text-align:center;
    cursor:pointer;
    transition:0.3s;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    position:relative;
    overflow:hidden;
}

.card:hover{
    transform:translateY(-6px);
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.card img{
    width:100%;
    height:170px;
    object-fit:cover;
    border-radius:10px;
}

.card h3{
    margin:10px 0 5px;
}

.card p{
    margin:3px;
    color:#555;
}

/* EDIT/DELETE OVERLAY */
.overlay{
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    opacity:0;
    transition:0.3s;
}

.card.active .overlay{
    opacity:1;
}

.overlay button{
    padding:8px 15px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
    font-weight:bold;
}

.edit-btn{ background:#2e7d32; }
.delete-btn{ background:#d32f2f; }

/* EMPTY */
.empty{
    text-align:center;
    margin-top:80px;
}

.empty img{
    width:140px;
}

/* BACK */
.back{
    text-align:center;
    margin-bottom:30px;
}

button.back-btn{
    padding:10px 25px;
    background:#555;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
</style>

<script>
// Search function
function searchCrop(){
    let input = document.getElementById("search").value.toLowerCase();
    let cards = document.getElementsByClassName("card");

    for(let i=0;i<cards.length;i++){
        let name = cards[i].getAttribute("data-name");
        cards[i].style.display = name.includes(input) ? "block" : "none";
    }
}

// Toggle overlay
function toggleOverlay(card){
    // Remove active from others
    let allCards = document.getElementsByClassName("card");
    for(let c of allCards) c.classList.remove("active");

    // Activate clicked card
    card.classList.add("active");
}
</script>
</head>

<body>

<div class="top">
    <h2>Your Crops</h2>
    <input type="text" id="search" class="search" placeholder="Search..." onkeyup="searchCrop()">
</div>

<?php if(mysqli_num_rows($res) > 0){ ?>
<div class="grid">

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<div class="card" data-name="<?php echo strtolower($row['crop_name']); ?>" onclick="toggleOverlay(this)">

<img src="uploads/<?php echo $row['crop_image']; ?>">
<h3><?php echo $row['crop_name']; ?></h3>
<p>Price per kg: ₹<?php echo $row['price']; ?></p>
<p>Quantity: <?php echo $row['quantity']; ?> kg</p>

<!-- Overlay -->
<div class="overlay">
    <button class="edit-btn" onclick="event.stopPropagation(); location.href='edit_crop.php?id=<?php echo $row['crop_id']; ?>'">Edit</button>
    <button class="delete-btn" onclick="event.stopPropagation(); if(confirm('Are you sure?')) location.href='delete_crop.php?id=<?php echo $row['crop_id']; ?>'">Delete</button>
</div>

</div>
<?php } ?>

</div>
<?php } else { ?>
<div class="empty">
    <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png">
    <h3>No crops added</h3>
</div>
<?php } ?>

<div class="back">
    <button class="back-btn" onclick="location.href='farmer_dashboard.php'">Back</button>
</div>

</body>
</html>