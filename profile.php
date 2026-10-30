<?php
session_start();
$conn = new mysqli("localhost","root","","cropsetu");

/* SECURITY */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH USER */
$res = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $res->fetch_assoc();

/* DEFAULT IMAGE */
$default_img = "images/profile.jpg";
$profile_pic = (!empty($user['profile_pic'])) ? $user['profile_pic'] : $default_img;

/* UPDATE PROFILE */
if(isset($_POST['update'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    /* IMAGE UPLOAD */
    if(!empty($_FILES['profile_pic']['name'])){
        $img = $_FILES['profile_pic']['name'];
        $tmp = $_FILES['profile_pic']['tmp_name'];
        $path = "uploads/".$img;
        move_uploaded_file($tmp,$path);
    } else {
        $path = $profile_pic;
    }

    $conn->query("UPDATE users SET 
        name='$name',
        email='$email',
        phone='$phone',
        location='$location',
        profile_pic='$path'
        WHERE id='$user_id'
    ");

    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:url('images/profile1.jpg') no-repeat center center fixed;
    background-size:cover;
}


/* container */
.container{
    width:90%;
    max-width:650px;
    margin:50px auto;
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

h2{
    text-align:center;
    color:#2e7d32;
}

/* profile image */
.profile-img{
    width:120px;
    height:120px;
    border-radius:50%;
    border:4px solid #4CAF50;
    object-fit:cover;
    display:block;
    margin:auto;
    cursor:pointer;
}

/* inputs */
input{
    width:100%;
    padding:10px;
    margin:8px 0;
    border-radius:10px;
    border:1px solid #ccc;
}

/* buttons */
button{
    width:100%;
    padding:12px;
    background:#4CAF50;
    color:#fff;
    border:none;
    border-radius:10px;
    margin-top:10px;
    cursor:pointer;
}

button:hover{
    background:#2e7d32;
}

/* back */
.back-btn{
    background:#555;
}
.back-btn:hover{
    background:#333;
}
</style>

<script>
function uploadPic(){
    document.getElementById("fileInput").click();
}
</script>

</head>

<body>

<div class="container">

<h2>👤 My Profile</h2>

<form method="POST" enctype="multipart/form-data">

<!-- PROFILE IMAGE -->
<img src="<?php echo $profile_pic; ?>" class="profile-img" onclick="uploadPic()">
<input type="file" name="profile_pic" id="fileInput" hidden>

<!-- DETAILS -->
<input type="text" name="name" value="<?php echo $user['name']; ?>" placeholder="Name" required>

<input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required>

<input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="Phone" required>

<input type="text" name="location" value="<?php echo $user['location']; ?>" placeholder="Location" required>

<!-- ROLE (ONLY DISPLAY) -->
<input type="text" value="<?php echo ucfirst($user['role']); ?>" readonly>

<!-- SAVE -->
<button type="submit" name="update">💾 Save Profile</button>

</form>

<br>

<!-- BACK BUTTON (AUTO BASED ON ROLE) -->
<?php if($user['role']=='farmer'){ ?>
<form action="farmer_dashboard.php">
<button class="back-btn">⬅ Back to Dashboard</button>
</form>
<?php } else { ?>
<form action="buyer_dashboard.php">
<button class="back-btn">⬅ Back to Dashboard</button>
</form>
<?php } ?>

</div>

</body>
</html