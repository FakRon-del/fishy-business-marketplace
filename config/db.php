<?php
$conn = new mysqli("localhost", "root", "", "fishy_business");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>