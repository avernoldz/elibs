<?php
// Include the database connection
include 'connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $email = $_POST['emailID'];
    $username = $_POST['userName'];
    $phone = $_POST['phoneNo'];
    $address = $_POST['address'];
    $password = $_POST['password']; // You should hash this password before saving

    // Validate required fields
    if (!empty($fullName) && !empty($email) && !empty($username) && !empty($phone) && !empty($password)) {

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL query for updating admin settings
        $query = "UPDATE admin SET full_name = ?, email = ?, username = ?, phone = ?, address = ?, password = ? WHERE id = 1"; // Assuming admin has ID 1

        // Initialize prepared statement
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("ssssss", $fullName, $email, $username, $phone, $address, $hashedPassword);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Settings updated successfully!";
            } else {
                echo "Error updating settings: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing the statement: " . $conn->error;
        }
    } else {
        echo "Please fill out all required fields!";
    }
}

$conn->close();
?>
