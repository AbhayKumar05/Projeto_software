<?php
 include 'config.php';
 session_start();
 $user_id = $_SESSION['user_id'];
 if(!isset($user_id)){
    header('location:login.php');
 }
 if(isset($_POST['add_to_cart'])){
   $product_name = $_POST['product_name'];
   $product_price = floatval($_POST['product_price']); // Ensure price is handled as float
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   
   // Check if product is already in the cart
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM cart WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'Já adicionado ao Carrinho!';
   }else{
      mysqli_query($conn, "INSERT INTO cart(user_id, name, price, quantity, image) 
      VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'Produto adicionado!';
   }
 }
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
    <p> <a href="home.php">Home</a> / Livros </p>
 </div>
 <section class="products">
    <div class="box-container">
       <?php  
          // Fetch products from the database
          $select_products = mysqli_query($conn, "SELECT * FROM products") or die('query failed');
          if(mysqli_num_rows($select_products) > 0){
             while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
       <form action="" method="post" class="box">
          <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
          <div class="name"><?php echo $fetch_products['name']; ?></div>
          <!-- Format the price to two decimal places for display -->
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
          }else{
             echo '<p class="empty">Sem produtos no momento!</p>';
          } 
        ?>
     </div>
  </section>
 <?php include 'footer.php'; ?>
 <script src="js/script.js"></script>
</body>
</html>
