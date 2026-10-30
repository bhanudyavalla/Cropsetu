<?php
include("db.php");
$res=mysqli_query($conn,"SELECT * FROM messages");
?>

<button onclick="history.back()">Back</button>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<p><?php echo $row['message']; ?></p>
<?php } ?>