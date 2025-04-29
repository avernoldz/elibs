<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
    $adminID = 1; // Replace with session-based admin ID
    $targetDir = "uploads/"; // Directory for storing avatars

    // Ensure upload directory exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate unique file name
    $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allowed image formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {
            // Update avatar path in database
            $query = "UPDATE admins SET avatar = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFilePath, $adminID);
            if ($stmt->execute()) {
                echo "Avatar updated successfully!";
                header("Location: account-settings.php"); // Redirect to profile page
                exit();
            } else {
                echo "Database update failed.";
            }
            $stmt->close();
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Invalid file type.";
    }
}

$conn->close();
?>
