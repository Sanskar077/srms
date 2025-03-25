<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if already logged in
if(isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

// Process login form
if(isset($_POST['login'])) {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    // Validate credentials
    if(validateLogin($username, $password)) {
        // Set session variables
        $_SESSION['admin_id'] = true;
        $_SESSION['username'] = $username;
        
        // Redirect to dashboard
        redirect('dashboard.php');
    } else {
        $error = 'Invalid username or password';
    }
}

// Redirect to index.php as it's the main login page
redirect('index.php');
?>
