<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_SESSION['admin_id'] ?? 1;

    // Collect form fields only if not empty
    $fields = [];
    $params = [];
    $types = '';

    if (!empty($_POST['fullName'])) {
        $fields[] = "full_name = ?";
        $params[] = $_POST['fullName'];
        $types .= 's';
    }
    if (!empty($_POST['emailID'])) {
        $fields[] = "email = ?";
        $params[] = $_POST['emailID'];
        $types .= 's';
    }
    if (!empty($_POST['userName'])) {
        $fields[] = "username = ?";
        $params[] = $_POST['userName'];
        $types .= 's';
    }
    if (!empty($_POST['phoneNo'])) {
        $fields[] = "phone_number = ?";
        $params[] = $_POST['phoneNo'];
        $types .= 's';
    }
    if (!empty($_POST['address'])) {
        $fields[] = "address = ?";
        $params[] = $_POST['address'];
        $types .= 's';
    }
    if (!empty($_POST['enterPassword'])) {
        $hashedPassword = password_hash($_POST['enterPassword'], PASSWORD_DEFAULT);
        $fields[] = "password = ?";
        $params[] = $hashedPassword;
        $types .= 's';
    }

    if (!empty($fields)) {
        $query = "UPDATE admins SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $adminID;
        $types .= 'i';

        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Settings updated successfully!";
            } else {
                $_SESSION['error_message'] = "Error updating settings: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error preparing the query: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "No fields to update.";
    }
}

$conn->close();
header("Location: account-settings.php");
exit();
?>
