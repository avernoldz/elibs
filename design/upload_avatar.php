<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["avatar"])) {
    $adminID = $_SESSION['admin_id'] ?? 1;
    $targetDir = "uploads/";

    // Create the uploads directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Generate a safe filename
    $originalName = basename($_FILES["avatar"]["name"]);
    $safeName = preg_replace("/[^a-zA-Z0-9\._-]/", "", $originalName);
    $fileName = time() . "_" . $safeName;
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {
            // Save only the filename in DB
            $query = "UPDATE admins SET avatar = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $fileName, $adminID);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Avatar updated successfully!";
            } else {
                $_SESSION['error_message'] = "Database update failed: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "File upload failed.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.";
    }
} else {
    $_SESSION['error_message'] = "No file selected or invalid request.";
}

$conn->close();
header("Location: account-settings.php");
exit();
