<?php
session_start();
include 'db.php';

if(isset($_POST['message'])){

    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    mysqli_query($conn,"
    INSERT INTO messages(sender_id, receiver_id, message)
    VALUES('$sender_id','$receiver_id','$message')
    ");

    header("Location: chat.php?user_id=".$receiver_id);
}
?>