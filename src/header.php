<?php
if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

// Defina valores padrão para evitar avisos de variáveis indefinidas
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Visitante';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Não disponível';

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <!--<div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p> Novo <a href="login.php">Login</a> | <a href="register.php">Registro</a> </p>
      </div>
   </div>-->
   <div class="header-2">
      <div class="flex">
         <div class="logo">
            <img src="images/headerlogo.png">
         </div>
         <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="shop.php">Shop</a>
            <a href="orders.php">Pedidos</a>
            <a href="contact.php">Contacto</a>
         </nav>
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php
            if ($user_id) {
               $select_cart_number = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'") or die('query failed');
               $cart_rows_number = mysqli_num_rows($select_cart_number);
            } else {
               $cart_rows_number = 0;
            }?>

            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>
         <div class="user-box">
            <p>Utilizador: <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>E-mail: <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">Logout</a>
         </div>
      </div>
   </div>
</header>