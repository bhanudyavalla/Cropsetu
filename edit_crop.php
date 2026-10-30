<?php
include("db.php");

if(!isset($_GET['id'])){
    header("Location: view_crops.php");
    exit();
}

$id = $_GET['id'];

// Fetch crop details
$res = mysqli_query($conn,"SELECT * FROM crops WHERE crop_id='$id'");
if(mysqli_num_rows($res)==0){
    echo "Crop not found!";
    exit();
}

$row = mysqli_fetch_assoc($res);

// Handle form submission
if(isset($_POST['submit'])){
    $name = $_POST['crop_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Image upload
    if($_FILES['crop_image']['name']){
        $image = time().'_'.$_FILES['crop_image']['name'];
        move_uploaded_file($_FILES['crop_image']['tmp_name'], "uploads/".$image);
    } else {
        $image = $row['crop_image'];
    }

    $update = mysqli_query($conn,"UPDATE crops SET crop_name='$name', price='$price', quantity='$quantity', crop_image='$image' WHERE crop_id='$id'");
    if($update){
        header("Location: view_crops.php");
        exit();
    } else {
        echo "Update failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Crop</title>
<style>
body{ font-family:Arial; background:url('images/leaf2.jpg'); background-size:cover; }
.container{ max-width:400px; margin:60px auto; background:white; padding:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.2);}
input,button{ width:100%; padding:10px; margin:8px 0; border-radius:6px; border:1px solid #ccc; }
button{ background:#2e7d32; color:white; border:none; cursor:pointer; }
.back-btn{ background:#555; margin-top:10px; }
</style>
</head>
<body>
<div class="container">
<h2>Edit Crop</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="crop_name" value="<?php echo $row['crop_name']; ?>" required>
    <input type="number" name="price" value="<?php echo $row['price']; ?>" required>
    <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" required>
    <input type="file" name="crop_image">
    <img src="uploads/<?php echo $row['crop_image']; ?>" width="100" style="margin-bottom:10px;">
    <button type="submit" name="submit">Update</button>
</form>
<button class="back-btn" onclick="location.href='view_crops.php'">Back</button>
</div>
</body>
</html>