<?php
include("db.php");
$res=mysqli_query($conn,"SELECT * FROM orders");
?>

<button onclick="history.back()">Back</button>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<div>
Order: <?php echo $row['quantity']; ?> kg  
Total: ₹<?php echo $row['total_price']; ?>
</div>
<?php } ?>