<?php
// Include connection file
include 'connection.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get student data from the form
    $lrn = $_POST['lrn']; // The unique student identifier
    
    $email = $_POST['email'];
    
    $grade_level = $_POST['grade_level'];
    $section = $_POST['section'];

    // Prepare the SQL query to update the student data
    $sql = "UPDATE students SET  email = ?, grade_level = ?, section = ? WHERE lrn = ?";

    // Use prepared statement to prevent SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi",  $email, $grade_level, $section, $lrn);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the student list page with success message
            header("Location: admin_studentlist.php?status=success");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
