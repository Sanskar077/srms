<?php ob_start(); ?>
<?php
session_start();
ob_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?>
