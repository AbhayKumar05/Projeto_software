<?php
//include 'config.php';
define('BASE_PATH', __DIR__);
include BASE_PATH . '/config.php';

 session_start();
 $user_id = $_SESSION['user_id'];
 if(!isset($user_id)){
    header('location:login.php');
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pedidos</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
 <?php include 'header.php'; ?>
 <div class="heading">
    <h3>Seus Pedidos</h3>
    <p> <a href="home.php">Home</a> / Pedidos </p>
 </div>
 <section class="placed-orders">
    <h1 class="title">Pedidos Feitos</h1>
    <div class="box-container">
       <?php 
          $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
          if(mysqli_num_rows($order_query) > 0){
             while($fetch_orders = mysqli_fetch_assoc($order_query)){
                ?>
                <div class="box">
                   <p> Feito em : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
                   <p> Nome : <span><?php echo $fetch_orders['name']; ?></span> </p>
                   <p> Numero : <span><?php echo $fetch_orders['number']; ?></span> </p>
                   <p> Email : <span><?php echo $fetch_orders['email']; ?></span> </p>
                   <p> Endereço : <span><?php echo $fetch_orders['address']; ?></span> </p>
                   <p> Metodo de pagamento : <span><?php echo $fetch_orders['method']; ?></span> </p>
                   <p> Seus pedidos : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
                   <p> Valor total : <span>$<?php echo number_format($fetch_orders['total_price'], 2, '.', ''); ?>/-</span> </p>
                   <p> Status de pagamento : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['payment_status']; ?></span> </p>
                </div>
                <?php
               }
           }else{
             echo '<p class="empty">Sem pedidos ainda!</p>';
            } 
         ?>
     </div>
  </section>
  <?php include 'footer.php'; ?>
  <script src="js/script.js"></script>
</body>
</html>
