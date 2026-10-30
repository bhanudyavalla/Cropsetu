<?php
include 'db.php';

if(isset($_POST['order_id'])){

    $id = $_POST['order_id'];

    // order details fetch
    $res = mysqli_query($conn,"SELECT * FROM orders WHERE id='$id'");
    $order = mysqli_fetch_assoc($res);

    $buyer_id = $order['buyer_id'];
    $crop_id = $order['crop_id'];

    // crop name fetch
    $crop_res = mysqli_query($conn,"SELECT crop_name FROM crops WHERE crop_id='$crop_id'");
    $crop = mysqli_fetch_assoc($crop_res);
    $crop_name = $crop['crop_name'];

    if(isset($_POST['accept'])){
        mysqli_query($conn,"UPDATE orders SET status='accepted' WHERE id='$id'");

        $msg = "Your order for $crop_name has been ACCEPTED";
        mysqli_query($conn,"INSERT INTO notifications(user_id,message) VALUES('$buyer_id','$msg')");
    }

    if(isset($_POST['reject'])){
        mysqli_query($conn,"UPDATE orders SET status='rejected' WHERE id='$id'");

        $msg = "Your order for $crop_name has been REJECTED";
        mysqli_query($conn,"INSERT INTO notifications(user_id,message) VALUES('$buyer_id','$msg')");
    }

    header("Location: orders.php");
}
?>