<?php
require 'vendor/autoload.php';
include 'config.php';

session_start();

$client = new \League\OAuth2\Client\Provider\Google([
    'clientId'     => '653769600039-4mbmbudces4bdbi6qrtbdb5jkfv5ognu.apps.googleusercontent.com',
    'clientSecret' => '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',
    'redirectUri' => 'http://localhost:9000/src/google-callback.php',  
]);

if (isset($_GET['code'])) {
    try {
        $token = $client->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $user = $client->getResourceOwner($token);
        $userName = $user->getName();
        $userEmail = $user->getEmail();

        $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$userEmail'");
        if (mysqli_num_rows($select_users) > 0) {
            $row = mysqli_fetch_assoc($select_users);
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
        } else {
            mysqli_query($conn, "INSERT INTO users (name, email) VALUES ('$userName', '$userEmail')");
            $_SESSION['user_name'] = $userName;
            $_SESSION['user_email'] = $userEmail;
            $_SESSION['user_id'] = mysqli_insert_id($conn);
        }

        header('Location: home.php');
        exit;
    } catch (Exception $e) {
        exit('Failed to get access token: ' . $e->getMessage());
    }
} else {
    exit('Authorization code not received');
}
?>
