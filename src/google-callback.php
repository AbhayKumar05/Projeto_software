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

if (isset($_GET['code'])) {
    try {
        // Exchange the authorization code for an access token
        $token = $client->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Get the user's details
        $user = $client->getResourceOwner($token);
        $userName = $user->getName();
        $userEmail = $user->getEmail();

        // Check if the user already exists in your database
        $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$userEmail'");
        if (mysqli_num_rows($select_users) > 0) {
            // User exists, log them in
            $row = mysqli_fetch_assoc($select_users);
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
        } else {
            // User does not exist, register them
            mysqli_query($conn, "INSERT INTO users (name, email) VALUES ('$userName', '$userEmail')");
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_email'] = $userEmail;
            $_SESSION['user_id'] = mysqli_insert_id($conn);
        }

        // Redirect to the home page
        header('Location: home.php');
        exit;
    } catch (Exception $e) {
        exit('Failed to get access token: ' . $e->getMessage());
    }
} else {
    exit('Authorization code not received');
}
