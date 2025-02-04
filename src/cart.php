<?php
// Include the database configuration file
define('BASE_PATH', __DIR__);
include BASE_PATH . '/config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

// Update cart item quantity
if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('Query failed');
    $message[] = 'Quantidade atualizada!';
}

// Delete a specific cart item
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('Query failed');
    header('location:cart.php');
    exit();
}

// Delete all cart items for the current user
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
    header('location:cart.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carrinho</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="heading">
    <h3>Compras</h3>
    <p><a href="home.php">Home</a> / Carrinho</p>
</div>

<section class="shopping-cart">
    <h1 class="title">Produtos Adicionados</h1>
    <div class="box-container">

        <?php 
        $grand_total = 0; 
        $select_cart = mysqli_query($conn, "
            SELECT c.*, p.author, g.name AS genre_name, p.price, p.image 
            FROM `cart` c 
            JOIN `products` p ON c.name = p.name 
            JOIN `genres` g ON p.genre_id = g.id 
            WHERE c.user_id = '$user_id'
        ") or die('Query failed');

        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {   
        ?>
        <div class="box">
            <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Tirar do Carrinho?');"></a>
            <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo htmlspecialchars($fetch_cart['name']); ?>">
            <div class="name"><?php echo htmlspecialchars($fetch_cart['name']); ?></div>
            <div class="author">Autor: <?php echo htmlspecialchars($fetch_cart['author']); ?></div>
            <div class="genre">Gênero: <?php echo htmlspecialchars($fetch_cart['genre_name']); ?></div>
            <div class="price">€<?php echo number_format($fetch_cart['price'], 2, '.', ''); ?></div>
            <form action="" method="post">
                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>" class="qty">
                <input type="submit" name="update_cart" value="Atualizar" class="option-btn">
            </form>
            <div class="sub-total"> Sub total: <span>€<?php echo number_format($fetch_cart['quantity'] * $fetch_cart['price'], 2, '.', ''); ?></span> </div>
        </div>
        <?php
            $grand_total += $fetch_cart['quantity'] * $fetch_cart['price'];
            }
        } else {
            echo '<p class="empty">Sem produtos adicionados</p>';
        } 
        ?>
    </div>

    <div style="margin-top: 2rem; text-align:center;">
        <a href="cart.php?delete_all" class="delete-btn <?php  echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Retirar tudo do carrinho?');">Retirar tudo</a>
    </div>

    <div class="cart-total">
        <p>Total Geral: <span>€<?php echo number_format($grand_total, 2, '.', ''); ?></span></p>
        <div class="flex">
            <a href="shop.php" class="option-btn">Continue Comprando</a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Finalizar</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
