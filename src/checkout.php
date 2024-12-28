<?php
include 'config.php';
require 'vendor/autoload.php'; 
session_start();

\Stripe\Stripe::setApiKey('sk_test_51QXsEiDzsayfXOwXcoVjTW9tLnaK3R6FcttLdYBgG23lDBNh824KGYIw4kjoz9B4jM4MVcDbuviTr5HInsNaTl63009OeKCb47'); 

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['order_btn'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $address = mysqli_real_escape_string($conn, 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code']);
   $placed_on = date('d-M-Y');
   $cart_total = 0;

   // Fetch cart items and calculate total
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if (mysqli_num_rows($cart_query) > 0) {
       while ($cart_item = mysqli_fetch_assoc($cart_query)) {
           $sub_total = ((float)$cart_item['price'] * (int)$cart_item['quantity']);
           $cart_total += $sub_total;
       }
   }

   if ($cart_total == 0) {
       $message[] = 'Your cart is empty';
   } else {
       try {
           $paymentIntent = \Stripe\PaymentIntent::create([
               'amount' => $cart_total * 100, 
               'currency' => 'eur',
               'payment_method_types' => ['card'], // Only card for now
               'metadata' => [
                   'user_id' => $user_id,
                   'name' => $name,
                   'email' => $email,
               ],
           ]);
           $_SESSION['client_secret'] = $paymentIntent->client_secret;
           header('Location: stripe_payment.php');
           exit;
       } catch (\Stripe\Exception\ApiErrorException $e) {
           error_log('PaymentIntent Error: ' . $e->getMessage());
           die('Error creating PaymentIntent: ' . $e->getMessage());
       }
   }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="heading">
        <h3>Checkout</h3>
        <p><a href="home.php">Home</a> / Checkout</p>
    </div>

    <!--<section class="display-order">
      <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>
    </section>-->

    <section class="checkout">
    <form action="" method="post" id="payment-form">
        <h3>Finalize Pedido</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Nome :</span>
                <input type="text" name="name" required placeholder="Enter your name">
            </div>
            <div class="inputBox">
                <span>Telefone :</span>
                <input type="text" name="number" required placeholder="Enter your number">
            </div>
            <div class="inputBox">
                <span>Email :</span>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="inputBox">
                <span>Morada :</span>
                <input type="text" name="flat" required placeholder="Flat nº">
            </div>
            <div class="inputBox">
                <span>Endereço :</span>
                <input type="text" name="street" required placeholder="Street name">
            </div>
            <div class="inputBox">
                <span>Cidade :</span>
                <input type="text" name="city" required placeholder="City">
            </div>
            <div class="inputBox">
                <span>Estado :</span>
                <input type="text" name="state" required placeholder="State">
            </div>
            <div class="inputBox">
                <span>País :</span>
                <input type="text" name="country" required placeholder="Country">
            </div>
            <div class="inputBox">
                <span>Codigo Postal :</span>
                <input type="text" name="pin_code" required placeholder="Postal code">
            </div>
        </div>
        
        <h3>Informações do Cartão</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Número do Cartão :</span>
                <input type="text" name="card_number" required placeholder="Enter card number">
                <!--<br>Successful payment: <strong>4242 4242 4242 4242</strong>
                    <br>Declined payment: <strong>4000 0000 0000 0002</strong>
                    <br>3D Secure: <strong>4000 0025 0000 3155</strong> -->
            </div>
            <div class="inputBox">
                <span>Data de Expiração (MM/YY) :</span>
                <input type="text" name="expiry_date" required placeholder="MM/YY">
            </div>
            <div class="inputBox">
                <span>Código de Segurança (CVC) :</span>
                <input type="text" name="cvc" required placeholder="CVC">
            </div>
        </div>
        <div class="btn-container">
           <input type="submit" value="Order Now" class="btn" name="order_btn">
        </div>
    </form>
</section>




    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>