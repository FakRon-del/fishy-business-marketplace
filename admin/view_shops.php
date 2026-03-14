<?php
include("../config/db.php");

$result = $conn->query("SELECT * FROM shops");
?>

<h2>All Shops</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Name</th>
<th>Description</th>
</tr>

<?php
while($row = $result->fetch_assoc()){
?>

<tr>

<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['description']; ?></td>

</tr>

<?php
}
?>

</table>

<br>

<a href="dashboard.php">Back to Dashboard</a>