<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QXsEiDzsayfXOwXcoVjTW9tLnaK3R6FcttLdYBgG23lDBNh824KGYIw4kjoz9B4jM4MVcDbuviTr5HInsNaTl63009OeKCb47'); // Replace with your secret key

$data = json_decode(file_get_contents('php://input'), true);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 10000, 
        'currency' => 'usd',
        'payment_method' => $data['paymentMethodId'],
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
