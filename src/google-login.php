<?php
require 'vendor/autoload.php';
include 'config.php'; 

session_start();

$client = new \League\OAuth2\Client\Provider\Google([
    'clientId'     => '653769600039-4mbmbudces4bdbi6qrtbdb5jkfv5ognu.apps.googleusercontent.com',      
    'clientSecret' => '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',  
    'redirectUri' => 'http://localhost:9000/src/google-callback.php',  
]);


if (!isset($_GET['code'])) {
    $authUrl = $client->getAuthorizationUrl([
        'scope' => ['email', 'profile'],
        'prompt' => 'select_account' 
    ]);
    $_SESSION['oauth2state'] = $client->getState();
    header('Location: ' . $authUrl);
    exit;
}
