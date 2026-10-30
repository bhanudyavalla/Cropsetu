<?php
$conn = new mysqli("localhost", "YOUR_USERNAME", "YOUR_PASSWORD", "cropsetu");

if ($conn->connect_error) {
    die("Connection failed");
}
?>