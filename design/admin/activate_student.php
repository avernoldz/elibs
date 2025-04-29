<?php
// Database connection
include 'connection.php';

// Check if the 'id' parameter is provided
if (isset($_GET['id'])) {
    // Sanitize and cast 'id' to avoid SQL injection
    $lrn = filter_var($_GET['id'], FILTER_SANITIZE_STRING);

    // Check if lrn is valid and not empty
    if (!empty($lrn)) {
        // Prepare the SQL statement to update the status to 'Active' (1)
        $stmt = $conn->prepare("UPDATE students SET status = 1 WHERE lrn = ?");
        $stmt->bind_param("s", $lrn);

        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect back to the student list after successful activation
            header("Location: admin_studentlist.php");
            exit();
        } else {
            // Log the error and display a user-friendly message
            error_log("Error activating student: " . $stmt->error);
            echo "An error occurred while activating the student. Please try again.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Invalid student ID.";
    }
} else {
    echo "No student ID provided.";
}

// Close connection
$conn->close();
?>
