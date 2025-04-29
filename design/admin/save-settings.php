<?php
session_start();
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $email = $_POST['emailID'];
    $username = $_POST['userName'];
    $phone = $_POST['phoneNo'];
    $address = $_POST['address'];
    $password = $_POST['enterPassword']; // You should hash this password before saving
    $adminID = $_SESSION['admin_id'] ?? 1; // Use session-based admin ID

    // Validate required fields
    if (!empty($fullName) && !empty($email) && !empty($username) && !empty($phone)) {
        // Prepare SQL query
        if (!empty($password)) {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query = "UPDATE admins SET full_name = ?, email = ?, username = ?, phone_number = ?, address = ?, password = ? WHERE id = ?";
        } else {
            // Update without changing password
            $query = "UPDATE admins SET full_name = ?, email = ?, username = ?, phone_number = ?, address = ? WHERE id = ?";
        }

        if ($stmt = $conn->prepare($query)) {
            if (!empty($password)) {
                $stmt->bind_param("ssssssi", $fullName, $email, $username, $phone, $address, $hashedPassword, $adminID);
            } else {
                $stmt->bind_param("sssssi", $fullName, $email, $username, $phone, $address, $adminID);
            }

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Settings updated successfully!";
            } else {
                $_SESSION['error_message'] = "Error updating settings: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Error preparing the statement: " . $conn->error;
        }
    } else {
        $_SESSION['error_message'] = "Please fill out all required fields!";
    }
}

// Close the database connection
$conn->close();

// Redirect back to the settings page
header("Location: account-settings.php");
exit();
?>
