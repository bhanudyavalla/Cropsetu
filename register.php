<!DOCTYPE html>
<html>
<head>
<title>CropSetu - Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    display:flex;
    height:100vh;
}

/* LEFT SIDE FORM */
.left{
    width:50%;
    padding:60px;
    background:#f9f9f9;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

/* RIGHT SIDE IMAGE */
.right{
    width:50%;
    background:url('images/carrot.png') no-repeat center center/cover;
    position:relative;
}

/* DARK OVERLAY */
.right::before{
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.3);
}

/* TEXT ON IMAGE */
.right-content{
    position:absolute;
    bottom:60px;
    left:40px;
    color:white;
    z-index:2;
}

.right-content h2{
    font-size:34px;
    margin-bottom:10px;
}

.right-content p{
    max-width:320px;
    line-height:1.5;
    opacity:0.9;
}

/* LOGO */
.logo{
    color:#2e7d32;
    font-weight:bold;
    font-size:22px;
    margin-bottom:20px;
}

/* HEADINGS */
h1{
    margin-bottom:10px;
}
.subtitle{
    color:#777;
    margin-bottom:30px;
}

/* INPUTS */
label{
    font-size:14px;
    font-weight:600;
    display:block;
    margin-top:15px;
}

input, select{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    margin-top:5px;
}

/* BUTTON */
button{
    width:100%;
    margin-top:25px;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#2e7d32;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}
button:hover{
    background:#1b4e20;
}

/* LINKS */
.bottom-links{
    margin-top:15px;
    font-size:13px;
}
.bottom-links a{
    color:#2e7d32;
    font-weight:bold;
    text-decoration:none;
    margin-left:5px;
}
.bottom-links a:hover{
    text-decoration:underline;
}

/* BACK BUTTON */
.back-home{
    margin-top:10px;
    font-size:13px;
}
.back-home a{
    text-decoration:none;
    color:#555;
}
.back-home a:hover{
    color:#000;
}

/* ERROR */
.msg{
    color:red;
    margin-top:10px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .right{display:none;}
    .left{width:100%;}
}
</style>
</head>

<body>

<!-- LEFT REGISTER -->
<div class="left">

    <div class="logo">🌱 CROPSETU</div>

    <h1>Create Your Account</h1>
    <div class="subtitle">Join our community of farmers and buyers</div>

    <form method="POST" action="register_action.php">

        <label>I am a</label>
        <select name="role" required>
            <option value="">Select your role</option>
            <option value="farmer">Farmer</option>
            <option value="buyer">Buyer</option>
        </select>

        <label>Full Name</label>
        <input type="text" name="name" placeholder="Your full name" required>

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="Your phone number" required>

        <label>Location</label>
        <input type="text" name="location" placeholder="City or village name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="your.email@example.com" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Create a strong password" required>

        <button type="submit" name="register">Create Account</button>

    </form>

    <div class="bottom-links">
        Already have an account? <a href="login.php">Login</a>
    </div>

    <div class="back-home">
        <a href="index.php">← Back to Home</a>
    </div>

</div>

<!-- RIGHT IMAGE -->
<div class="right">
    <div class="right-content">
        <h2>Grow Your Business</h2>
        <p>Whether you're a farmer or buyer, CROPSETU helps you succeed.</p>
    </div>
</div>

</body>
</html>