<?php
    include "connection.php"; // Include database connection

    // Check if a session has already started, if not, start it
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Define session timeout duration (e.g., 30 minutes)
    $session_timeout_duration = 1800; // 30 minutes (in seconds)

    // Check if the session has timed out
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout_duration) {
        // If the session has timed out, clear session and redirect to the login page
        session_unset(); // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: login.php?timeout=true"); // Redirect with timeout indicator
        exit();
    }

    // Update last activity time stamp
    $_SESSION['last_activity'] = time();

    // Check if the user is logged in and has the role of 'admin'
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['role'] !== 'admin') {
        // If the user is not logged in or doesn't have the 'admin' role, redirect to login
        header("Location: login.php?error=not_logged_in");
        exit();
    }

    // Retrieve the admin username from the session, default to 'Admin' if not set
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

    // Optional: Additional check to ensure the username is set
    if ($username === 'Admin') {
        header("Location: login.php?error=invalid_user");
        exit();
    }

    // At this point, the admin is logged in and valid, and the session is active
?>