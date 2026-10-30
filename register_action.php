<?php
include 'db.php';

if(isset($_POST['register'])){

    $role = $_POST['role'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>
                alert('Email already exists!');
                window.location.href='register.php';
              </script>";
        exit;
    }

    // Insert user
    $sql = "INSERT INTO users (role, name, phone, location, email, password)
            VALUES ('$role', '$name', '$phone', '$location', '$email', '$password')";

    if(mysqli_query($conn, $sql)){
        echo "<script>
                alert('Registration successful!');
                window.location.href='login.php';
              </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>