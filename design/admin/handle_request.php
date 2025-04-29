<?php
require 'connection.php'; // Connect to your database

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $request_id = intval($_GET['id']);
    
    // Determine the status based on the action
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    
    // Update the book request status
    $stmt = $conn->prepare("UPDATE book_requests SET status = ? WHERE request_id = ?");
    $stmt->bind_param("si", $status, $request_id);
    
    if ($stmt->execute()) {
        echo "Request successfully updated.";
    } else {
        echo "Error updating request: " . $conn->error;
    }
    
    $stmt->close();
    
    // Redirect back to the admin requests page
    header('Location: admin_requests.php');
    exit();
}
?>
