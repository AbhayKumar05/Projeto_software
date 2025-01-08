<?php
 include_once __DIR__ . '/src/config.php';
 session_start();
 session_unset();
 session_destroy();
 header('location:login.php');
?>

<!-- maybe could be used to logout - To Clear Existing Tokens/Sessions
session_start();
session_destroy();
-!>