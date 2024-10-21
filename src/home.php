<?php
 include 'config.php';
/* session_start(); 
 $user_id = $_SESSION['user_id'];

 if(!isset($user_id)){
    header('location:login.php');
  } 

 if(isset($_POST['add_to_cart'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
       $message[] = 'Ja esta no carrinho!';
    }else{
       mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
       $message[] = 'Produto adicionado!';
      }
   }*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<?php include 'header.php'; ?>
<section class="home">
    <div class="main">
        <div class="main_image">
            <img src="uploaded_img/hero.png" alt="Book Store Image">
        </div>
        <div class="main_tag">
            <h3>Bem Vindo a<br><span>Ventorim's Book Store</span></h3>
            <div class="cormorant-garamond-light"> 
            <p id = "hero_p">
                Bem-vindo à nossa encantadora livraria virtual, um refúgio literário onde as palavras ganham vida e a imaginação se expande sem limites. 
                Aqui, mergulhe em um universo repleto de páginas cheias de histórias emocionantes, conhecimento profundo e aventuras inesquecíveis.
            </p>
            </div>
        </div>
    </div>
</section>


<!-- Services Section -->
   <section class='home_service'>
      <div class="full-width-services">
         <div class="services-row">
            <div class="service-item">
                <i class="fa-solid fa-truck-fast"></i>
                <div class="service-content">
                  <h3>Envio Rápido</h3>
                  <p>
                     Contamos com uma equipe para envio de encomendas que trabalha com eficiência.
                  </p>
                </div>
             </div>
             <div class="service-item">
                 <i class="fa-solid fa-headset"></i>
                 <div class="service-content">
                     <h3>Serviços 24 x 7</h3>
                     <p>
                         Trabalhamos 24h, 7 dias por semana, para seu melhor atendimento.
                     </p>
                 </div>
             </div>
             <div class="service-item">
                 <i class="fa-solid fa-tag"></i>
                 <div class="service-content">
                     <h3>Melhores ofertas</h3>
                     <p>
                         Temos as melhores ofertas de mercado, procurando sempre o conforto de nossos clientes.
                     </p>
                 </div>
             </div>
             <div class="service-item">
                 <i class="fa-solid fa-lock"></i>
                 <div class="service-content">
                     <h3>Pagamentos seguros</h3>
                     <p>
                         Temos verificação no momento de pagamento, garantindo sempre a segurança de nossos clientes.
                    </p>
                 </div>
             </div>
         </div>
     </div>
 </section>



   <section class="products"> 
    <h1 href="shop.php" class="cormorant-garamond-bold">Ultimos Lançamentos</h1> <!--------class='.cormorant-garamond-light'------->
     <div class="box-container">
        <?php /*
          $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
          if(mysqli_num_rows($select_products) > 0){
           while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
       <form action="" method="post" class="box">
          <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
          <div class="name"><?php echo $fetch_products['name']; ?></div>
          <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
          <input type="number" min="1" name="product_quantity" value="1" class="qty">
          <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
          <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
          <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
          <input type="submit" value="add to cart" name="add_to_cart" class="btn">
       </form>
       <?php
           }
          }else{
          echo '<p class="empty">Sem produtos no momento!</p>';
          }*/
        ?>
     </div>

    <!--<div class="load-more" style="margin-top: 2rem; text-align:center">
       <a href="shop.php" class="option-btn">Veja mais</a>
    </div>-->
 </section>
<!--
 <section class="about">
    <div class="flex">
       <div class="image">
          <img src="images/Ventorim's Book Store.png" alt="">
       </div>

       <div class="content">
            <h1 class="cormorant-garamond-bold">Sobre Nós</h1>  
          <p>Nossa livraria é um oásis para todos os amantes da leitura, um lugar onde cada livro é uma porta para novos horizontes. 
            Buscamos sempre proporcinar a melhor experiencia para nossos leitores.</p>
          <a href="about.php" class="white-btn">Veja Mais</a>
       </div>
    </div> 
 </section>
-->
 <section class="image-section">
   <div class="image-container first-image">
      <img src="images/home_book4.png" alt="First Image">
   </div>
   <div class="image-container second-image">
      <img src="images/home_book1.png" alt="Second Image">
   </div>
   <div class="image-container first-image">
      <img src="images/home_book3.png" alt="Third Image">
   </div>
</section>


<!-- Our Story Section -->
<section class="our-story-section">
   <h1>Our Story - A Nossa História</h1>
   <p>Na Ventorim's Book Store, acreditamos que os livros têm o poder de transformar vidas. Somos mais do que uma livraria; somos um espaço onde a imaginação não tem limites e cada página vira uma nova descoberta.</p>
   <p>Fundada com o objetivo de aproximar leitores de todo o mundo, oferecemos uma vasta seleção de títulos que despertam emoções, desafiam perspectivas e promovem o conhecimento. Cada livro é escolhido com cuidado, garantindo uma experiência única a cada leitura. Seja para explorar os grandes clássicos, descobrir novos talentos ou aprofundar o seu conhecimento, a nossa missão é proporcionar momentos inesquecíveis através do prazer da leitura. Venha fazer parte desta jornada connosco.</p>
</section>

<!--<div class="right-section">
        <section class="newsletter-section">
            <h2>Fique Conectado!</h2>
            <p>Inscreva-se na nossa newsletter para receber as últimas novidades, promoções e muito mais diretamente no seu email.</p>
            <form action="subscribe.php" method="post" class="newsletter-form">
                <input type="email" name="email" placeholder="Digite o seu email" required>
                <button type="submit" class="btn">Subscrever</button>
            </form>
            <p class="small-text">Não enviamos spam. Pode cancelar a subscrição a qualquer momento.</p>
        </section>
</div>-->


 <section class="recommendations">
   <h1 class="cormorant-garamond-bold">Recomendações com base em compras anteriores</h1>
   <div class="flex-books">
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 1">
         <p>---TITLE--- 1</p>
      </div>
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 2">
         <p>---TITLE--- 2</p>
      </div>
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 3">
         <p>---TITLE--- 3</p>
      </div>
   </div>
   
   <div class="flex-books">
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 4">
         <p>---TITLE--- 4</p>
      </div>
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 5">
         <p>---TITLE--- 5</p>
      </div>
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 6">
         <p>---TITLE--- 6</p>
      </div>
   </div>
</section>

<section class="home-contact">
    <div class="content">
       <h1 class="cormorant-garamond-bold">Tem questões?</h1>
       <p>Junte-se a nós e faça parte de uma comunidade que vive e respira histórias!
         Se inscreva no nosso blog para descobrir mais e para ficar sempre atualizado.</p>
       <a href="contact.php" class="white-btn">Entre em contacto</a>
    </div>
 </section>

<!--------------------------------------------        Book Recomendation List here       ----------------------------------------------->

 <?php include 'footer.php'; ?>

 <script src="js/script.js"></script>

</body>
</html>