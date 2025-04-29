<?php
// Database connection
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lrn = $_POST['lrn'];

    // Fetch the current status of the student
    $stmt = $conn->prepare("SELECT status FROM students WHERE lrn = ?");
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Change the status
    $new_status = $status == 1 ? 0 : 1; // Toggle status
    $stmt = $conn->prepare("UPDATE students SET status = ? WHERE lrn = ?");
    $stmt->bind_param("is", $new_status, $lrn);
    
    if ($stmt->execute()) {
        // Redirect back to the student list with a success message
        header("Location: studentlist.php?message=Status changed successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: studentlist.php?error=Failed to change status");
        exit();
    }
}

// Close the database connection
$conn->close();
?>
