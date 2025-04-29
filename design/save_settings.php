<?php
// Include the database connection
include 'connection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data, handling cases where the user might leave fields empty
    $fullName = !empty($_POST['fullName']) ? $_POST['fullName'] : null;
    $emailID = !empty($_POST['emailID']) ? $_POST['emailID'] : null;
    $userName = !empty($_POST['userName']) ? $_POST['userName'] : null;
    $phoneNo = !empty($_POST['phoneNo']) ? $_POST['phoneNo'] : null;
    $address = !empty($_POST['address']) ? $_POST['address'] : null;
    $password = !empty($_POST['enterPassword']) ? $_POST['enterPassword'] : null;

    // Assume adminID is available through session or any other method
    $adminID = 1; // Replace with session ID or the correct method to identify the admin

    // Start building the SQL query to only update fields that are provided
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
        // Hash the password before saving it to the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query .= "password = ?, ";
        $params[] = $hashedPassword;
        $types .= 's';
    }

    // Remove the trailing comma and space from the query
    $query = rtrim($query, ', ');

    // Add the condition to target the specific admin by ID
    $query .= " WHERE id = ?";

    // Add the admin ID to the parameters
    $params[] = $adminID;
    $types .= 'i';

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($query)) {
        // Dynamically bind the parameters based on which fields were set
        $stmt->bind_param($types, ...$params);

        // Execute the query
        if ($stmt->execute()) {
            echo "Settings updated successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
