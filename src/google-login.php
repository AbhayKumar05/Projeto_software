<?php
require 'vendor/autoload.php';
include 'config.php'; // Include your database configuration

session_start();

// Configure the Google OAuth client
$client = new \League\OAuth2\Client\Provider\Google([
    'clientId'     => '653769600039-4mbmbudces4bdbi6qrtbdb5jkfv5ognu.apps.googleusercontent.com',      
    'clientSecret' => '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',  
    'redirectUri'  => 'http://localhost:8888',  
]);

if (!isset($_GET['code'])) {
    // Generate a URL for Google's authorization page
    $authUrl = $client->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $client->getState();
    header('Location: ' . $authUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    // Check for CSRF attacks
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}
