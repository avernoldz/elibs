<?php
include 'connection.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['student_id'])) {
    die("Unauthorized access!");
}

$studentID = $_SESSION['student_id']; // Get student ID from session
$targetDir = "uploads/"; // Directory for storing avatars

// Ensure upload directory exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Check if a file was uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
    $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allowed image formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($fileType, $allowedTypes)) {
        // Move uploaded file
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {
            // Update avatar path in database
            $query = "UPDATE students SET avatar = ? WHERE student_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFilePath, $studentID);
            if ($stmt->execute()) {
                // Redirect with success message
                echo "<script>
                        alert('Avatar updated successfully!');
                        window.location.href = 'student_account_setting.php';
                      </script>";
                exit();
            } else {
                echo "<script>alert('Database update failed.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('File upload failed.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
    }
}

$conn->close();
?>
