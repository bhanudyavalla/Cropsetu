<?php
session_start();
$conn = new mysqli("localhost","root","","cropsetu");

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check buyer credentials
    $res = $conn->query("SELECT * FROM buyers WHERE email='$email' AND password='$password'");

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $_SESSION['buyer_id'] = $row['buyer_id'];       // ✅ session set
        $_SESSION['buyer_name'] = $row['buyer_name'];   // optional
        header("Location: buyer_dashboard.php");
        exit();
    }else{
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Buyer Login - Cropsetu</title>
<style>
body{
    margin:0;
    font-family:Arial;
    background:url('images/design.jpg') no-repeat center center fixed;
    background-size:cover;
}
.login-box{
    background:rgba(255,255,255,0.95);
    max-width:400px;
    margin:100px auto;
    padding:30px;
    border-radius:12px;
    text-align:center;
}
input{
    width:80%;
    padding:10px;
    margin:10px 0;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    padding:10px 25px;
    border:none;
    border-radius:6px;
    background:#2e7d32;
    color:white;
    cursor:pointer;
}
.error{
    color:red;
}
</style>
</head>
<body>
<div class="login-box">
<h2>Buyer Login</h2>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
<input type="email" name="email" placeholder="Email" required><br>
<input type="password" name="password" placeholder="Password" required><br>
<button type="submit" name="login">Login</button>
</form>
</div>
</body>
</html>