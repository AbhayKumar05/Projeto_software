 <?php /*
// Database connection details
$host = 'localhost';
$port = '8889'; // MAMP MySQL port
$dbname = 'shop_db';
$user = 'root';
$password = 'root';

// Establish the MySQL connection
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}*/
$conn = mysqli_connect('localhost','root','root','shop_db') or die('connection failed');
?>
