<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Update the products and cart table to use FLOAT for price fields
mysqli_query($conn, "ALTER TABLE `products` MODIFY COLUMN `price` FLOAT NOT NULL") or die('Query failed');
mysqli_query($conn, "ALTER TABLE `cart` MODIFY COLUMN `price` FLOAT NOT NULL") or die('Query failed');

// Alter total_price in orders table to FLOAT
mysqli_query($conn, "ALTER TABLE `orders` MODIFY COLUMN `total_price` FLOAT NOT NULL") or die('Query failed');

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = floatval($_POST['product_price']);
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Já está no carrinho!';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
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
   <title>Home</title>

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

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
            <img src="uploaded_img/hero2.jpg" alt="Book Store Image">
        </div>
        <div class="main_tag">
             <div class="cormorant-garamond-medium">
                 <h3>Encontre o Teu<br><span>Próximo Livro</span></h3> 
            </div>
            <div class="cormorant-garamond-light"> 
            <p id = "hero_p">
                A nossa encantadora livraria virtual, um refúgio literário onde as palavras ganham vida e a imaginação se expande sem limites. 
            </p>
            </div>
            <button type="submit" id="hero-btn">Explore!</button>
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
    <!-- Section header -->
    <!-- <h1 href="shop.php" class="cormorant-garamond-bold">Ultimos Lançamentos</h1>  -->


    <!-- Container for product boxes -->
    <!-- <div class="box-container"> -->
        <?php 
        /*
          // Query the database to select up to 6 products

 <section class="products"> 
    <h1 href="shop.php" class="cormorant-garamond-bold">Ultimos Lançamentos</h1> 
     <div class="box-container">
        <?php 

          $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
          
          // Check if there are products in the database
          if(mysqli_num_rows($select_products) > 0){
           // Loop through each product and display it
           while($fetch_products = mysqli_fetch_assoc($select_products)){
        */
        ?>
       <!-- Form for individual product -->
       <!-- <form action="" method="post" class="box"> -->
          <!-- Product image -->
          <!-- <img class="image" src="uploaded_img/<?php // echo $fetch_products['image']; ?>" alt=""> -->
          <!-- Product name -->
          <!-- <div class="name"><?php // echo $fetch_products['name']; ?></div> -->
          <!-- Product price -->
          <!-- <div class="price">$<?php // echo $fetch_products['price']; ?>/-</div> -->
          <!-- Quantity input -->
          <!-- <input type="number" min="1" name="product_quantity" value="1" class="qty"> -->
          <!-- Hidden inputs for product details -->
          <!-- <input type="hidden" name="product_name" value="<?php // echo $fetch_products['name']; ?>"> -->
          <!-- <input type="hidden" name="product_price" value="<?php // echo $fetch_products['price']; ?>"> -->
          <!-- <input type="hidden" name="product_image" value="<?php // echo $fetch_products['image']; ?>"> -->
          <!-- Submit button to add product to cart -->
          <!-- <input type="submit" value="add to cart" name="add_to_cart" class="btn"> -->
       <!-- </form> -->
       <?php
       /*
           }
          }else{
          // Display a message if no products are available
          echo '<p class="empty">Sem produtos no momento!</p>';
          }
        */
        ?>
     <!-- </div> -->

    <!-- Link to view more products -->
    <!-- <div class="load-more" style="margin-top: 2rem; text-align:center"> -->
       <!-- <a href="shop.php" class="option-btn">Veja mais</a> -->
    <!-- </div> -->
 </section>



 
 
 <section class="carousel">
    <h1 class="cormorant-garamond-bold">Últimos Lançamentos</h1>
    <div class="carousel-container">
        <div class="carousel-items">
            <?php
            include 'config.php';

            $fetch_products_query = mysqli_query($conn, "SELECT * FROM `products` ORDER BY id DESC LIMIT 5") or die('Query failed');

            if(mysqli_num_rows($fetch_products_query) > 0) {
                while($product = mysqli_fetch_assoc($fetch_products_query)) {
                    ?>
                    <div class="carousel-item">
                        <img src="uploaded_img/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="carousel-image">
                        <div class="carousel-item-info">
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price">€<?php echo number_format($product['price'], 2); ?></p>
                            <form action="" method="post">
                                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                                <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                                <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
                                <input type="number" name="product_quantity" value="1" min="1" class="quantity-box">
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                    <span class="material-icons">local_mall</span> Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">Sem produtos disponíveis!</p>';
            }
            ?>
        </div>
    </div>
</section>



<section class="carousel">
    <h1 class="cormorant-garamond-bold">Últimos Lançamentos</h1>
    <div class="carousel-container">
        <div class="carousel-items">
            <!-- Book 1 -->
            <div class="carousel-item">
                <img src="images/book__2.jpg" alt="Book 1" class="carousel-image">
                <div class="carousel-item-info">
                    <h3>Paleoarctic</h3>
                    <p class="price">€19.99</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
            </div>
            <!-- Book 2 -->
            <div class="carousel-item">
                <img src="images/book__1.jpg" alt="Book 2" class="carousel-image">
                <div class="carousel-item-info">
                    <h3>The Bass Rock</h3>
                    <p class="price">€24.99</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
            </div>
            <!-- Book 3 -->
            <div class="carousel-item">
                <img src="images/book__3.jpg" alt="Book 3" class="carousel-image">
                <div class="carousel-item-info">
                    <h3>Peter and the Wolf</h3>
                    <p class="price">€17.50</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
            </div>
            <!-- Book 4 -->
            <div class="carousel-item">
                <img src="images/book__4.jpg" alt="Book 4" class="carousel-image">
                <div class="carousel-item-info">
                    <h3>Call me by your Name</h3>
                    <p class="price">€22.99</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
            </div>
            <!-- Book 5 -->
            <div class="carousel-item">
                <img src="images/home_book3.png" alt="Book 1" class="carousel-image" onclick="openModal('Paleoarctic', '€19.99', 'images/home_book3.png', 'This riveting paperback explores the lives of six remarkable female pharaohs, from Hatshepsut to Cleopatra, and shines a piercing light on perceptions of powerful women today. Regularly, repeatedly, and with impunity, queens like Hatshepsut, Nefertiti.')">
                <div class="carousel-item-info">
                    <h3>Paleoarctic</h3>
                    <p class="price">€19.99</p>
                    <a href="cart.php">
                        <button class="add-to-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                        </button>
                    </a>   
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Working pop-up 
<div class="carousel-item">
    <img src="images/home_book3.png" alt="Book 1" class="carousel-image" onclick="openModal('Paleoarctic', '€19.99', 'images/home_book3.png', 'This riveting paperback explores the lives of six remarkable female pharaohs, from Hatshepsut to Cleopatra, and shines a piercing light on perceptions of powerful women today. Regularly, repeatedly, and with impunity, queens like Hatshepsut, Nefertiti.')">
    <div class="carousel-item-info">
        <h3>Paleoarctic</h3>
        <p class="price">€19.99</p>
        <a href="cart.php">
            <button class="add-to-cart-btn">
                <span class="material-icons">local_mall</span> Add to Cart
            </button>
        </a>   
    </div>
</div>-->

<!-- POP-UP 
<div id="bookModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <div class="modal-left">
            <img id="modalImage" class="modal-image" src="" alt="Book Image">
            <h3 id="modalTitle">Title</h3>
            <p id="modalPrice" class="modal-price">Price</p>
        </div>
        <div class="modal-right">
            <p id="modalDescription">Description</p>
            <button class="add-to-cart-btn">
                <span class="material-icons">local_mall</span> Add to Cart
            </button>
        </div>
    </div>
</div>-->


<!-- POP-UP -->
<div id="bookModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>

        <!-- Main content: left and right sections -->
        <div class="modal-main">
            <div class="modal-left">
                <img id="modalImage" class="modal-image" src="" alt="Book Image">
                <h3 id="modalTitle">Title</h3>
                <p id="modalPrice" class="modal-price">Price</p>
            </div>
            <div class="modal-right">
                <p id="modalDescription">Description</p>
                <button class="add-to-cart-btn">
                    <span class="material-icons">local_mall</span> Add to Cart
                </button>
            </div>
        </div>

        <div class="recommended-books">
            <h4 id="bookrec">Livros recomendados</h4>
            <div class="recommended-items">
                <div class="recommended-item">
                    <img src="images/book__2.jpg" alt="Book 1" class="recommended-image">
                    <h4 id="modaltexts">Paleoarctic</h4>
                    <p class="price" id="modaltexts">€19.99</p>
                    <a href="cart.php">
                        <button class="add-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                        </button>
                    </a>   
                </div>
                <div class="recommended-item">
                    <img src="images/book__1.jpg" alt="Book 2" class="recommended-image">
                    <h4 id="modaltexts">Paleoarctic</h4>
                    <p class="price" id="modaltexts">€19.99</p>
                    <a href="cart.php">
                        <button class="add-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                        </button>
                    </a>
                </div>
                <div class="recommended-item">
                    <img src="images/book__3.jpg" alt="Book 3" class="recommended-image">
                    <h4 id="modaltexts">Paleoarctic</h4>
                    <p class="price" id="modaltexts">€19.99</p>
                    <a href="cart.php">
                        <button class="add-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                        </button>
                    </a>
                </div>
                <div class="recommended-item">
                    <img src="images/book__4.jpg" alt="Book 4" class="recommended-image">
                    <h4 id="modaltexts">Paleoarctic</h4>
                    <p class="price" id="modaltexts">€19.99</p>
                    <a href="cart.php">
                        <button class="add-cart-btn">
                            <span class="material-icons">local_mall</span> Add to Cart
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>






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


<section class="our-story-section">
   <h1>Our Story - A Nossa História</h1>
   <p>Na Ventorim's Book Store, acreditamos que os livros têm o poder de transformar vidas. Somos mais do que uma livraria; somos um espaço onde a imaginação não tem limites e cada página vira uma nova descoberta.</p>
   <p>Fundada com o objetivo de aproximar leitores de todo o mundo, oferecemos uma vasta seleção de títulos que despertam emoções, desafiam perspectivas e promovem o conhecimento. Cada livro é escolhido com cuidado, garantindo uma experiência única a cada leitura. Seja para explorar os grandes clássicos, descobrir novos talentos ou aprofundar o seu conhecimento, a nossa missão é proporcionar momentos inesquecíveis através do prazer da leitura. Venha fazer parte desta jornada connosco.</p>
</section>

 <section class="recommendations">
   <h1 class="cormorant-garamond-bold">Recomendações com base em compras anteriores</h1>
   <div class="flex-books">
      <div class="book">
         <img id="home_rec" src="images/book__3.jpg" alt="Book 1">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€22.59</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/book__5.jpg" alt="Book 6">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€9.50</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/book__4.jpg" alt="Book 2">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€17.99</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/home_book3.png" alt="Book 3">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€17.99</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
   </div>
   
   <div class="flex-books">
      <div class="book">
         <img id="home_rec" src="images/book__2.jpg" alt="Book 4">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€22.59</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/book__1.jpg" alt="Book 5">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€9.50</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/book__5.jpg" alt="Book 6">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€9.50</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
      </div>
      <div class="book">
         <img id="home_rec" src="images/book__3.jpg" alt="Book 6">
         <div class="carousel-item-info">
                    <h3>Título do Livro</h3>
                    <p class="price">€9.50</p>
                    <button class="add-to-cart-btn">
                        <span class="material-icons">local_mall</span> Add to Cart
                    </button>
                </div>
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

 <?php include 'footer.php'; ?>


 <script src="js/script.js"></script>
 <script src="js/home.js"></script>

</body>
</html>