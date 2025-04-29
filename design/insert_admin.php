<?php
include 'connection.php'; // Make sure this file contains your database connection details

// Define the username and password you want to insert
$username = 'admin1'; // Replace with your desired username
$password = 'PSHSADMIN2024'; // Replace with the actual password

// Hash the password using bcrypt
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare the SQL statement to insert the new admin
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

// Execute the statement and check if successful
if ($stmt->execute()) {
    echo "Admin user inserted successfully.";
} else {
    echo "Error inserting admin: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
