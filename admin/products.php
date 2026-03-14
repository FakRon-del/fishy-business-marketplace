<?php
include("../config/db.php");

$result = $conn->query("SELECT * FROM products");
?>

<h2>Manage Products</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Price</th>
<th>Action</th>
</tr>

<?php
while($row = $result->fetch_assoc()){
?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<img src="../images/<?php echo $row['image']; ?>" width="60">
</td>

<td><?php echo $row['name']; ?></td>

<td>₱<?php echo $row['price']; ?></td>

<td>

<a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |

<a href="delete_product.php?id=<?php echo $row['id']; ?>">Delete</a>

</td>

</tr>

<?php
}
?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>