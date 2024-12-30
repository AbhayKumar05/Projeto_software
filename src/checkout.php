<?php
include 'config.php';
require 'vendor/autoload.php';
session_start();

// Configuração da chave secreta do Stripe
\Stripe\Stripe::setApiKey('sk_test_51QXsEiDzsayfXOwXcoVjTW9tLnaK3R6FcttLdYBgG23lDBNh824KGYIw4kjoz9B4jM4MVcDbuviTr5HInsNaTl63009OeKCb47');

// Verificação se o usuário está logado
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// Inicialização da mensagem
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_btn'])) {
    // Sanitização dos inputs do usuário
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $number = mysqli_real_escape_string($conn, trim($_POST['number']));
    $email = mysqli_real_escape_string($conn, filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL));
    $address = mysqli_real_escape_string($conn, 'Flat no. ' . trim($_POST['flat']) . ', ' . trim($_POST['street']) . ', ' . trim($_POST['city']) . ', ' . trim($_POST['state']) . ', ' . trim($_POST['country']) . ' - ' . trim($_POST['pin_code']));
    $placed_on = date('Y-m-d H:i:s');
    $cart_total = 0;

    // Verificação de e-mail válido
    if (!$email) {
        $message[] = 'Por favor, insira um email válido.';
    }

    // Calcular o total do carrinho
    $cart_query = $conn->prepare("SELECT price, quantity FROM cart WHERE user_id = ?");
    $cart_query->bind_param('i', $user_id);
    $cart_query->execute();
    $cart_result = $cart_query->get_result();

    if ($cart_result->num_rows > 0) {
        while ($cart_item = $cart_result->fetch_assoc()) {
            $sub_total = ((float)$cart_item['price'] * (int)$cart_item['quantity']);
            $cart_total += $sub_total;
        }
    } else {
        $message[] = 'Seu carrinho está vazio. Por favor, adicione itens antes de continuar.';
    }

    // Verificar se há itens no carrinho antes de criar o PaymentIntent
    if ($cart_total > 0 && empty($message)) {
        try {
            // Obter o PaymentMethod ID do frontend (exemplo, ajuste se necessário)
            $paymentMethodId = $_POST['payment_method_id'] ?? null;

            if (!$paymentMethodId) {
                $message[] = 'Método de pagamento inválido. Tente novamente.';
            } else {
                // Criar PaymentIntent no Stripe
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $cart_total * 100, // Converter para centavos
                    'currency' => 'eur',
                    'payment_method_types' => ['card'], // Garantir que "card" está habilitado
                    'payment_method' => $paymentMethodId, // Receber este ID do frontend
                    'confirm' => true, // Confirmar o pagamento automaticamente
                    'metadata' => [
                        'user_id' => $user_id,
                        'name' => $name,
                        'email' => $email,
                    ],
                ]);

                // Armazenar informações na sessão
                $_SESSION['client_secret'] = $paymentIntent->client_secret;
                $_SESSION['order_details'] = [
                    'name' => $name,
                    'number' => $number,
                    'email' => $email,
                    'address' => $address,
                    'total_price' => $cart_total,
                    'products' => 'Lista de produtos do carrinho', // Ajustar se necessário
                ];

                header('Location: stripe_payment.php');
                exit;
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            error_log('Erro do Stripe: ' . $e->getMessage());
            $message[] = 'Erro ao criar o pagamento: ' . $e->getMessage();
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
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="heading">
        <h3>Checkout</h3>
        <p><a href="home.php">Home</a> / Checkout</p>
    </div>

    <section class="checkout">
        <form action="" method="post">
            <h3>Finalize Pedido</h3>
            <?php if (!empty($message)): ?>
                <div class="message">
                    <?php foreach ($message as $msg): ?>
                        <p><?php echo htmlspecialchars($msg); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
                <input type="hidden" name="payment_method_id" id="payment-method-id">
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
