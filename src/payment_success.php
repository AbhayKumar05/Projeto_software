<?php
//include 'config.php';
define('BASE_PATH', __DIR__);
include BASE_PATH . '/config.php';

session_start();

if (!isset($_GET['payment_intent'])) {
    header('location:checkout.php');
    exit();
}

require_once 'stripe-php/init.php';

\Stripe\Stripe::setApiKey(getenv('sk_test_51QXsEiDzsayfXOwXcoVjTW9tLnaK3R6FcttLdYBgG23lDBNh824KGYIw4kjoz9B4jM4MVcDbuviTr5HInsNaTl63009OeKCb47'));

try {
    $paymentIntentId = $_GET['payment_intent'];
    $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

    if ($paymentIntent->status === 'succeeded') {
        // Verificar se os dados da sessão estão disponíveis
        $user_id = $_SESSION['user_id'] ?? null;
        $order_details = $_SESSION['order_details'] ?? [];

        if (!$user_id || empty($order_details)) {
            throw new Exception('Session data missing or invalid.');
        }

        // Inserir o pedido no banco de dados
        $stmt = $conn->prepare("
            INSERT INTO orders 
            (user_id, name, number, email, method, address, total_products, total_price, placed_on) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            'issssssss',
            $user_id,
            $order_details['name'],
            $order_details['number'],
            $order_details['email'],
            $order_details['method'],
            $order_details['address'],
            $order_details['products'],
            $order_details['total_price'],
            date('Y-m-d H:i:s')
        );

        if ($stmt->execute()) {
            // Limpar o carrinho do usuário
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            $successMessage = "Thank you for your purchase! Your order has been placed successfully.";
        } else {
            throw new Exception('Failed to save order in the database.');
        }
    } else {
        throw new Exception('Payment not successful.');
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="heading">
        <h3>Payment Status</h3>
        <p><a href="home.php">Home</a> / Payment Success</p>
    </div>

    <section class="payment-status">
        <div class="container">
            <?php if (isset($successMessage)): ?>
                <h1 class="success">Payment Successful</h1>
                <p><?php echo $successMessage; ?></p>
                <h4>Order Details</h4>
                <p><b>Name:</b> <?php echo htmlspecialchars($order_details['name']); ?></p>
                <p><b>Email:</b> <?php echo htmlspecialchars($order_details['email']); ?></p>
                <p><b>Address:</b> <?php echo htmlspecialchars($order_details['address']); ?></p>
                <p><b>Products:</b> <?php echo htmlspecialchars($order_details['products']); ?></p>
                <p><b>Total Price:</b> €<?php echo htmlspecialchars($order_details['total_price']); ?></p>
                <a href="home.php" class="btn">Return Home</a>
            <?php else: ?>
                <h1 class="error">Payment Failed</h1>
                <p><?php echo htmlspecialchars($errorMessage ?? 'Something went wrong. Please try again.'); ?></p>
                <a href="checkout.php" class="btn">Try Again</a>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
