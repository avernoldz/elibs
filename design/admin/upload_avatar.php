<?php

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
    $adminID = $_SESSION['admin_id'] ?? 1; // Use session-based admin ID

    $targetDir = "uploads/"; // Avatar storage directory

    // Ensure upload directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique filename
    $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["avatar"]["name"]));
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed image formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {
            // Store only the file name in the database
            $query = "UPDATE admins SET avatar = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $fileName, $adminID);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Avatar updated successfully!";
                header("Location: account-settings.php"); // Redirect to profile page
                exit();
            } else {
                $_SESSION['error_message'] = "Database update failed.";
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "File upload failed.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
    }
}

// Close the database connection
$conn->close();
header("Location: account-settings.php");
exit();
?>
