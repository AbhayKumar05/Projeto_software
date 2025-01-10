<?php
//include 'config.php';
define('BASE_PATH', __DIR__);
include BASE_PATH . '/config.php';

session_start();

// Check if user is logged in by checking user_id in session
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Redirect to login if user_id is not set
    header('Location: login.php');
    exit(); // Ensure the script stops execution after the redirect
}

$user_id = $_SESSION['user_id']; // Get the logged-in user_id

if (isset($_POST['add_to_cart'])) {
    // Sanitize user inputs to prevent SQL injection
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = floatval($_POST['product_price']);
    $product_image = mysqli_real_escape_string($conn, $_POST['product_image']);
    $product_quantity = intval($_POST['product_quantity']);

    // Check if the product is already in the cart for the current user
    $check_cart_numbers = mysqli_query($conn, "
        SELECT * FROM cart 
        WHERE name = '$product_name' AND user_id = '$user_id'
    ") or die('Query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Já adicionado ao Carrinho!';
    } else {
        // Insert the product into the cart
        mysqli_query($conn, "
            INSERT INTO cart(user_id, name, price, quantity, image) 
            VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')
        ") or die('Query failed');
        $message[] = 'Produto adicionado!';
    }
}

// Define allowed genres
$allowed_genres = [
    "Romance", "Novela", "Conto", "Crônica", "Poema", "Canção", "Drama histórico", "Teatro de vanguarda",
    "Fantasia", "Ficção científica", "Distopia", "Ação e aventura", "Ficção Policial", "Horror",
    "Thriller e Suspense", "Ficção histórica", "Ficção Feminina", "LGBTQ+", "Ficção Contemporânea",
    "Realismo mágico", "Graphic Novel", "Infantil", "Memórias e autobiografia", "Biografia", "Gastronomia", "Arte e Fotografia", "Autoajuda",
    "História", "Viagem", "Crimes Reais", "Humor", "Ensaios", "Guias & Como fazer", 
    "Religião e Espiritualidade", "Humanidades e Ciências Sociais", "Paternidade e família",
    "Tecnologia e Ciência"
];

// Get the books that match the allowed genres
$db_books_query = mysqli_query($conn, "
    SELECT p.*, g.name AS genre_name 
    FROM products p 
    JOIN genres g ON p.genre_id = g.id
    WHERE g.name IN ('" . implode("','", $allowed_genres) . "')
") or die('Query failed');

// Fetch the results into an array
$db_books = [];
if (mysqli_num_rows($db_books_query) > 0) {
    while ($row = mysqli_fetch_assoc($db_books_query)) {
        $db_books[] = $row;
    }
}

// API key for Google services (ensure it's kept secure and not exposed publicly)
$apiKey = 'AIzaSyClHlr6d3zfBKlaiziJ-MxPuoh3RGjn75U';

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shop</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="heading">
    <h3>Livros</h3>
    <p><a href="home.php">Home</a> / Livros</p>
</div>

<section class="products">
    <div class="box-container">
       <?php
       foreach ($db_books as $fetch_products) {
           if (!empty($fetch_products['name']) && !empty($fetch_products['author']) && !empty($fetch_products['genre_name']) && !empty($fetch_products['image'])) {
               ?>
               <form action="" method="post" class="box">
                  <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <div class="author">Autor: <?php echo $fetch_products['author']; ?></div>
                  <div class="genre">Gênero: <?php echo $fetch_products['genre_name']; ?></div>
                  <div class="price">€<?php echo number_format($fetch_products['price'], 2, '.', ''); ?></div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                  <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                    <span class="material-icons">local_mall</span> Add to Cart
                  </button>
               </form>
               <?php
           }
       }

       foreach ($allowed_genres as $genre) {
           $googleBooksApiUrl = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($genre) . "&key=" . $apiKey;
           $response = file_get_contents($googleBooksApiUrl);
           $googleBooksData = [];
           if ($response) {
               $googleBooksData = json_decode($response, true)['items'] ?? [];
           }

           if (!empty($googleBooksData)) {
               foreach ($googleBooksData as $book) {
                   $bookTitle = $book['volumeInfo']['title'] ?? null;
                   $bookAuthor = implode(', ', $book['volumeInfo']['authors'] ?? []);
                   $bookGenre = $genre;
                   $bookImage = $book['volumeInfo']['imageLinks']['thumbnail'] ?? null;
                   $bookPrice = rand(15, 50);

                   if ($bookTitle && $bookAuthor && $bookGenre && $bookImage) {
                       ?>
                       <form action="" method="post" class="box">
                          <img class="image" src="<?php echo $bookImage; ?>" alt="">
                          <div class="name"><?php echo $bookTitle; ?></div>
                          <div class="author">Autor: <?php echo $bookAuthor; ?></div>
                          <div class="genre">Gênero: <?php echo $bookGenre; ?></div>
                          <div class="price">€<?php echo number_format($bookPrice, 2, '.', ''); ?></div>
                          <input type="number" min="1" name="product_quantity" value="1" class="qty">
                          <input type="hidden" name="product_name" value="<?php echo $bookTitle; ?>">
                          <input type="hidden" name="product_price" value="<?php echo $bookPrice; ?>">
                          <input type="hidden" name="product_image" value="<?php echo $bookImage; ?>">
                          <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                          </button>
                       </form>
                       <?php
                   }
               }
           }
       }
       ?>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
