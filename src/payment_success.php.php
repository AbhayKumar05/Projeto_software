<?php 
session_start();
require 'config.php'; 

if (!isset($_GET['payment_intent'])) {
    header('location:checkout.php');
    exit();
}

\Stripe\Stripe::setApiKey('your_stripe_secret_key');

try {
    $paymentIntentId = $_GET['payment_intent'];
    $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

    if ($paymentIntent->status === 'succeeded') {
 
        $user_id = $_SESSION['user_id'] ?? null;
        $order_details = $_SESSION['order_details'] ?? [];

        if (!$user_id || empty($order_details)) {
            throw new Exception('Session data missing or invalid.');
        }

        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: auto;
            padding: 20px;
        }
        .message {
            text-align: center;
            margin-top: 50px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="message">
        <?php if (isset($successMessage)): ?>
            <h1 class="success">Payment Successful</h1>
            <p><?php echo $successMessage; ?></p>
            <a href="home.php" class="btn">Return Home</a>
        <?php else: ?>
            <h1 class="error">Payment Failed</h1>
            <p><?php echo $errorMessage ?? 'Something went wrong. Please try again.'; ?></p>
            <a href="checkout.php" class="btn">Try Again</a>
        <?php endif; ?>
    </div>
</body>
</html>
