<?php
// Include the database connection
include 'connection.php';
session_start();

// Initialize an array to store the response
$response = array();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve the book_id from POST data
    $book_id = !empty($_POST['book_id']) ? $_POST['book_id'] : null;

    // Ensure the necessary data is provided
    if ($book_id) {
        // SQL query to select reviews by book_id
        $query = "SELECT students.name, reviews.* FROM reviews INNER JOIN students ON reviews.student_id = students.student_id WHERE book_id = ? LIMIT 50";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $types = 'i'; // 'i' for integer (book_id)
            $stmt->bind_param($types, $book_id); // Bind the parameter

            // Execute the query
            $stmt->execute();

            // Get the result set
            $result = $stmt->get_result();

            // Check if any reviews are found
            if ($result->num_rows > 0) {
                // If reviews are found, fetch and return them
                $reviews = array();
                while ($row = $result->fetch_assoc()) {

                    $row['created'] = date('m/d/Y h:i A', strtotime($row['created']));
                    $reviews[] = $row;
                }

                // Return the results as a response
                $response['status'] = 1;  // Success status
                $response['message'] = "Reviews fetched successfully.";
                $response['data'] = $reviews;  // Include the reviews data
            } else {
                // No reviews found
                $response['status'] = 2;
                $response['message'] = "No reviews found for this book.";
            }

            // Close the statement
            $stmt->close();
        } else {
            // If the query preparation fails
            $response['status'] = 3;
            $response['message'] = "Error preparing the query: " . $conn->error;
        }
    } else {
        // If book_id is missing
        $response['status'] = 4;
        $response['message'] = "Missing required data (book_id).";
    }
}

// Close the connection
$conn->close();

// Output the response as JSON
echo json_encode($response);
