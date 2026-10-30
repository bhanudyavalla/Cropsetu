<?php
session_start();
require_once "db.php"; // Make sure this points to your DB connection

$msg = "";

// Check if form is submitted
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        $msg = "Please enter both email and password.";
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            $user = $result->fetch_assoc();

            // If you stored plain passwords (not recommended)
            if($password === $user['password']){
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                if($user['role'] == 'farmer'){
                    header("Location: farmer_dashboard.php");
                    exit();
                } elseif($user['role'] == 'buyer'){
                    header("Location: buyer_dashboard.php");
                    exit();
                } elseif($user['role']=='admin'){
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $msg = "Unknown user role!";
                }
            } else {
                $msg = "Invalid email or password.";
            }

        } else {
            $msg = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>CropSetu Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    display:flex;
    height:100vh;
    flex-direction:row; /* ensures left/right placement */
}
.left{
    width:50%;
    padding:60px;
    background:#f9f9f9;
    display:flex;
    flex-direction:column;
    justify-content:center;
    order:2;
}
.right{
    width:50%;
    background:url('images/plant5.png') no-repeat center center/cover;
    position:relative;
    order:1;
}
.right::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}
.right-content{
    position:absolute;
    bottom:60px;
    left:40px;
    color:white;
    z-index:2;
}
.right-content h2{font-size:34px;margin-bottom:10px;}
.right-content p{max-width:320px;line-height:1.5;opacity:0.9;}
.logo{color:#2e7d32;font-weight:bold;font-size:22px;margin-bottom:20px;}
h1{margin-bottom:10px;}
.subtitle{color:#777;margin-bottom:30px;}
label{font-size:14px;font-weight:600;display:block;margin-top:15px;}
input{width:100%;padding:12px;border-radius:8px;border:1px solid #ddd;margin-top:5px;}
button{width:100%;margin-top:25px;padding:14px;border:none;border-radius:8px;background:#2e7d32;color:white;font-size:16px;font-weight:bold;cursor:pointer;}
button:hover{background:#1b4e20;}
.bottom-text{margin-top:15px;font-size:14px;}
.bottom-text a{color:#2e7d32;font-weight:bold;text-decoration:none;}
.back-home{margin-top:20px;}
.back-home a{text-decoration:none;color:#555;}
.back-home a:hover{color:#000;}
.msg{color:red;margin-top:10px;}
@media(max-width:900px){
    .right{display:none;}
    .left{width:100%; order:1;}
}
</style>
</head>
<body>

<div class="right">
    <div class="right-content">
        <h2>Direct from Farm to Buyers</h2>
        <p>Join CROPSETU and connect directly with farmers and buyers.</p>
    </div>
</div>

<div class="left">
    <div class="logo">🌱 CROPSETU</div>
    <h1>Welcome Back</h1>
    <div class="subtitle">Login to your account to continue</div>

    <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="your@email.com" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <div class="bottom-text">
        Don’t have an account? <a href="register.php">Register here</a>
    </div>

    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>
</div>

</body>
</html>