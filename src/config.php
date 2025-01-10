<?php
$conn = mysqli_connect('db', 'root', 'root', 'shop_db', 3306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>