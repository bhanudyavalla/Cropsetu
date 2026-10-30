<?php
include("db.php");

if(!isset($_GET['id'])){
    header("Location: view_crops.php");
    exit();
}

$id = $_GET['id'];

// Optional: Delete image file
$res = mysqli_query($conn,"SELECT crop_image FROM crops WHERE crop_id='$id'");
if($res && mysqli_num_rows($res)>0){
    $row = mysqli_fetch_assoc($res);
    if(file_exists("uploads/".$row['crop_image'])){
        unlink("uploads/".$row['crop_image']);
    }
}

// Delete crop
mysqli_query($conn,"DELETE FROM crops WHERE crop_id='$id'");

header("Location: view_crops.php");
exit();
?>