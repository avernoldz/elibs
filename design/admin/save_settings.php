<?php
session_start();
include 'connection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data (only update fields that are provided)
    $fullName = !empty($_POST['fullName']) ? $_POST['fullName'] : null;
    $emailID = !empty($_POST['emailID']) ? $_POST['emailID'] : null;
    $userName = !empty($_POST['userName']) ? $_POST['userName'] : null;
    $phoneNo = !empty($_POST['phoneNo']) ? $_POST['phoneNo'] : null;
    $address = !empty($_POST['address']) ? $_POST['address'] : null;
    $password = !empty($_POST['enterPassword']) ? $_POST['enterPassword'] : null;

    // Assume adminID is available through session
    $adminID = $_SESSION['admin_id'] ?? 1; // Replace with the correct session ID

    // Start building the SQL query dynamically
    $query = "UPDATE admins SET ";
    $params = [];
    $types = '';

    if ($fullName !== null) {
        $query .= "full_name = ?, ";
        $params[] = $fullName;
        $types .= 's';
    }
    if ($emailID !== null) {
        $query .= "email = ?, ";
        $params[] = $emailID;
        $types .= 's';
    }
    if ($userName !== null) {
        $query .= "username = ?, ";
        $params[] = $userName;
        $types .= 's';
    }
    if ($phoneNo !== null) {
        $query .= "phone_number = ?, ";
        $params[] = $phoneNo;
        $types .= 's';
    }
    if ($address !== null) {
        $query .= "address = ?, ";
        $params[] = $address;
        $types .= 's';
    }
    if ($password !== null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query .= "password = ?, ";
        $params[] = $hashedPassword;
        $types .= 's';
    }

    // Remove the trailing comma and space from the query
    $query = rtrim($query, ', ');

    // Add the condition to target the specific admin by ID
    $query .= " WHERE id = ?";
    $params[] = $adminID;
    $types .= 'i';

    // Prepare and execute query
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
}

// Close connection and redirect
$conn->close();
header("Location: account-settings.php");
exit();
?>
