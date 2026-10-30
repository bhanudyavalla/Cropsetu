<?php
// edit_user.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "cropsetu";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
}

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $role = $_POST['role'];

    $conn->query("UPDATE users SET name='$name', email='$email', mobile='$mobile', role='$role' WHERE id=$id");
    header("Location: admin.php");
    exit();
}
?>

<form method="POST" class="p-8 bg-white max-w-md mx-auto mt-20 rounded-xl shadow-lg">
  <h2 class="text-2xl font-bold text-green-700 mb-6">Edit User</h2>
  <input type="text" name="name" value="<?php echo $user['name']; ?>" placeholder="Name" class="w-full border px-4 py-2 mb-4 rounded">
  <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" class="w-full border px-4 py-2 mb-4 rounded">
  <input type="text" name="phone" value="<?php echo $user['phone']; ?>" placeholder="phone" class="w-full border px-4 py-2 mb-4 rounded">
  <select name="role" class="w-full border px-4 py-2 mb-4 rounded">
    <option value="farmer" <?php if($user['role']=='farmer') echo 'selected'; ?>>Farmer</option>
    <option value="buyer" <?php if($user['role']=='buyer') echo 'selected'; ?>>Buyer</option>
  </select>
  <button type="submit" name="update" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-600 transition">Update</button>
</form>