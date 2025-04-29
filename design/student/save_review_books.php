<?php
// Include the database connection
include 'connection.php';
session_start();

// Initialize an array to store the response
$response = array();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data, handling cases where the user might leave fields empty
    $student_id = $_SESSION['student_id']; // Assuming the student ID is stored in the session
    $book_id = !empty($_POST['book_id']) ? $_POST['book_id'] : null;
    $rating = !empty($_POST['rating']) ? $_POST['rating'] : null;
    $review = !empty($_POST['review']) ? $_POST['review'] : null;

    // Ensure the necessary data is provided
    if ($book_id && $rating !== null && $review !== null) {
        // SQL query to insert the review
        $query = "INSERT INTO reviews(`student_id`, `book_id`, `rating`, `review`) VALUES(?, ?, ?, ?)";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Define the types of the parameters
            $types = 'siis'; // 's' for string (student_id, review), 'i' for integer (book_id, rating)
            $stmt->bind_param($types, $student_id, $book_id, $rating, $review); // Bind the parameters

            // Execute the query
            if ($stmt->execute()) {
                $response['status'] = 1;  // Success status
                $response['message'] = "Review saved successfully.";
            } else {
                // If the query execution fails
                $response['status'] = 3;
                $response['message'] = "Error executing query: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            // If the query preparation fails
            $response['status'] = 3;
            $response['message'] = "Error preparing the query: " . $conn->error;
        }
    } else {
        // If any required field is missing
        $response['status'] = 2;
        $response['message'] = "Missing required data (book_id, rating, or review).";
    }
}

// Close the connection
$conn->close();

// Output the response as JSON
echo json_encode($response);
