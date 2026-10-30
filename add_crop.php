<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$farmer_id = $_SESSION['user_id'] ?? 0;
$userName = $_SESSION['username'] ?? 'Farmer';

$message = "";

if(isset($_POST['submit'])){
    $crop_name = $conn->real_escape_string($_POST['crop_name']);
    $quantity = (float)$_POST['quantity'];
    $price_per_kg = (float)$_POST['price_per_kg'];
    $description = $conn->real_escape_string($_POST['description'] ?? '');

    // MULTIPLE IMAGE UPLOAD (MAX 10)
    $image_names = [];
    if(isset($_FILES['crop_images'])){
        $totalFiles = min(count($_FILES['crop_images']['name']), 10);

        for($i=0;$i<$totalFiles;$i++){
            if($_FILES['crop_images']['error'][$i] == 0){
                $ext = pathinfo($_FILES['crop_images']['name'][$i], PATHINFO_EXTENSION);
                $newName = "crop_".time()."_".$i.".".$ext;
                move_uploaded_file($_FILES['crop_images']['tmp_name'][$i], "uploads/".$newName);
                $image_names[] = $newName;
            }
        }
    }

    $imagesStr = implode(",", $image_names);

    $conn->query("INSERT INTO crops (farmer_id, crop_name, quantity, price_per_kg, description, image)
                  VALUES ($farmer_id, '$crop_name', $quantity, $price_per_kg, '$description', '$imagesStr')");

    $message = "Crop added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Crop</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
  font-family:'Poppins',sans-serif;
  background:#f8fafc;
  margin:0;
}

/* Layout */
.main-wrapper{display:flex;min-height:100vh;}

/* Sidebar (NO CHANGE) */
.sidebar{
  width:100px;
  background:linear-gradient(to bottom,#14532d,#166534,#22c55e);
  display:flex;
  flex-direction:column;
  align-items:center;
  padding:30px 0;
  border-top-right-radius:40px;
  border-bottom-right-radius:40px;
}

.profile-box{
  width:60px;height:60px;background:white;color:#166534;
  border-radius:20px;display:flex;align-items:center;justify-content:center;
  font-size:24px;font-weight:bold;position:relative;margin-bottom:40px;text-decoration:none;
}

.profile-dot{
  width:14px;height:14px;background:#22c55e;border-radius:50%;
  border:2px solid white;position:absolute;top:-2px;right:-2px;
}

.side-btn{
  width:58px;height:58px;border-radius:18px;
  background:rgba(255,255,255,0.15);color:white;border:none;
  font-size:20px;margin-bottom:18px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:0.3s;
}

.side-btn:hover{background:rgba(255,255,255,0.25);}
.active-btn{background:white;color:#166534;}
.logout-btn{background:#ef4444;margin-top:auto;}

/* Content */
.content{
  flex:1;
  padding:40px;
  display:flex;
  flex-direction:column;
  align-items:center;
}

/* Banner */
.top-banner{
  width:100%;
  max-width:1000px;
  background:linear-gradient(135deg,#0f766e,#3b82f6);
  color:white;
  padding:30px 40px;
  border-radius:25px;
  font-size:28px;
  font-weight:bold;
  margin-bottom:40px;
}

/* Card */
.card{
  width:100%;
  max-width:1000px;
  background:white;
  border-radius:30px;
  padding:40px;
  box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

/* Grid */
.form-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
}

/* Inputs */
input, textarea{
  width:100%;
  padding:14px;
  border-radius:14px;
  border:1px solid #e5e7eb;
  margin-bottom:20px;
}

input:focus, textarea:focus{
  border-color:#14b8a6;
  outline:none;
}

/* Upload */
.upload-box{
  border:2px dashed #cbd5e1;
  padding:40px;
  border-radius:20px;
  text-align:center;
}

/* Button */
.submit-btn{
  width:100%;
  padding:15px;
  border:none;
  border-radius:16px;
  background:linear-gradient(135deg,#0f766e,#3b82f6);
  color:white;
  font-size:18px;
  font-weight:bold;
  cursor:pointer;
}

/* Message */
.message{
  background:#d1fae5;
  padding:12px;
  border-radius:10px;
  margin-bottom:20px;
  text-align:center;
}
</style>
</head>

<body>

<div class="main-wrapper">

<!-- Sidebar -->
<div class="sidebar">
  <a href="user_profile.php" class="profile-box">
    <?= strtoupper(substr($userName,0,1)) ?>
    <div class="profile-dot"></div>
  </a>

  <button onclick="window.location.href='farmer_dashboard.php'" class="side-btn">
    <i class="fas fa-house"></i>
  </button>

  <button class="side-btn active-btn">
    <i class="fas fa-plus-circle"></i>
  </button>

  <button onclick="window.location.href='farmer_orders.php'" class="side-btn">
    <i class="fas fa-cart-shopping"></i>
  </button>

  <a href="farmer_messages.php" class="side-btn">
    <i class="fas fa-comments"></i>
  </a>

  <button onclick="window.location.href='logout.php'" class="side-btn logout-btn">
    <i class="fas fa-right-from-bracket"></i>
  </button>
</div>

<!-- Content -->
<div class="content">

  <div class="top-banner">Add New Crop 🌾</div>

  <div class="card">

    <?php if($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">

      <div class="form-grid">

        <!-- LEFT -->
        <div>
          <label>Upload Images (max 10)</label>
          <div class="upload-box">
            <i class="fas fa-upload text-2xl mb-2"></i>
            <input type="file" name="crop_images[]" multiple accept="image/*">
          </div>
        </div>

        <!-- RIGHT -->
        <div>
          <label>Crop Name</label>
          <input type="text" name="crop_name" required>

          <label>Quantity (kg)</label>
          <input type="number" name="quantity" required>

          <label>Price per kg</label>
          <input type="number" name="price_per_kg" required>

          <label>Description</label>
          <textarea name="description"></textarea>

          <button type="submit" name="submit" class="submit-btn">
            Add Crop 🚀
          </button>
        </div>

      </div>

    </form>

  </div>

</div>

</div>

</body>
</html>