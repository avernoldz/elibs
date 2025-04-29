<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login if not logged in
    header('Location: landingPage.php');
    exit;
}

// If the user is logged in, display the protected content
echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
echo "<p>This is the protected content that only admins can see.</p>";

// Include a logout option
echo '<a href="logout.php">Logout</a>';
?>
