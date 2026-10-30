<?php
// delete_user.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM users WHERE id=$id");
}

$conn->close();
header("Location: admin.php");
exit();
?>