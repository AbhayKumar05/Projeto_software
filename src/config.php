<?php
<<<<<<< HEAD
$conn = mysqli_connect('localhost', 'root', 'root', 'shop_db');
=======
<<<<<<< HEAD
$conn = mysqli_connect('db', 'root', 'root', 'shop_db', 3306);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
=======
$conn = mysqli_connect('localhost', 'root', 'root', 'shop_db');
>>>>>>> Abhay
>>>>>>> main
//$conn = mysqli_connect('localhost', 'root', 'root', 'shop_db') or die('connection failed');
?>
