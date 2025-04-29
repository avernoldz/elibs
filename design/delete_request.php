<?php
// Include the database connection file
include 'connection.php';

// Start the session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the request_id from the URL
if (isset($_GET['request_id']) && !empty($_GET['request_id'])) {
    $request_id = (int)$_GET['request_id'];

    // Delete the request from the database
    $sql = "DELETE FROM book_requests WHERE request_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $request_id);
        if ($stmt->execute()) {
            // Set a success message
            $_SESSION['msg'] = "Request deleted successfully.";
        } else {
            $_SESSION['msg'] = "Failed to delete request.";
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Failed to prepare the deletion statement.";
    }
} else {
    $_SESSION['msg'] = "Invalid request.";
}

// Close the connection
$conn->close();

// Redirect to my_requests.php with a message
header("Location: student_borrow.php");
exit();
?>
