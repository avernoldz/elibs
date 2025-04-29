<?php
session_start(); // Start the session

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or landing page
header("Location: login.php");
exit;
?>
