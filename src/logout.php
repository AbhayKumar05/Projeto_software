<?php
//include 'config.php';
define('BASE_PATH', __DIR__);
include BASE_PATH . '/config.php';

 session_start();
 session_unset();
 session_destroy();
 header('location:login.php');
?>

<!-- maybe could be used to logout - To Clear Existing Tokens/Sessions
session_start();
session_destroy();
-!>