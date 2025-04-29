<?php
// Include your database connection
include 'connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $emailID = $_POST['emailID'];
    $userName = $_POST['userName'];
    $phoneNo = $_POST['phoneNo'];
    $address = $_POST['address'];
    $password = $_POST['enterPassword']; // Handle password securely
    // Perform any necessary validation here

    // Optional: Hash the password for security (use only if you're updating the password)
    // $password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare an SQL statement
    $stmt = $conn->prepare("UPDATE users SET full_name=?, email=?, username=?, phone=?, address=?, password=? WHERE user_id=?");
    
    // Assuming you have a way to get the current user's ID, for example, from session
    $userId = $_SESSION['user_id']; // Replace with actual user ID logic

    // Bind parameters
    $stmt->bind_param("ssssssi", $fullName, $emailID, $userName, $phoneNo, $address, $password, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Account settings updated successfully.";
    } else {
        echo "Error updating account settings: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
