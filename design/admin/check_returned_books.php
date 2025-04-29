<?php
// Include the database connection
include 'connection.php';

// Initialize an array to store the response
$response = array();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data, handling cases where the user might leave fields empty
    $qr_data = !empty($_POST['qr_data']) ? $_POST['qr_data'] : null;

    if ($qr_data) {

        $query = "

            SELECT Student.name, BookRequest.request_date, Student.lrn, Book.isbn, 
            Book.title, Book.author, BookRequest.request_id, BookRequest.status
            FROM books AS Book
            INNER JOIN book_requests AS BookRequest ON Book.book_id = BookRequest.book_id
            INNER JOIN students AS Student ON Student.student_id = BookRequest.student_id

            WHERE BookRequest.status = 'Approved' AND BookRequest.request_id = ?

            ";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Define the types of the parameters
            $types = 's'; // Assuming `isbn` is a string. If it's an integer, change this to 'i'.
            $stmt->bind_param($types, $qr_data); // Bind the qr_data to the placeholder

            // Execute the query
            if ($stmt->execute()) {
                // Fetch the result (if any)
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $response['status'] = 1;  // Success status
                    $response['data'] = array();

                    while ($row = $result->fetch_assoc()) {
                        // Store each row's data in the response
                        $row['request_date'] = date('m/d/Y', strtotime($row['request_date']));
                        $response['data'][] = $row;
                    }
                } else {
                    // If no data found
                    $response['status'] = 2;
                    $response['message'] = "The provided ISBN does not exist in the system.";
                }
            } else {
                $response['status'] = 3;
                $response['message'] = "Error executing query: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $response['status'] = 3;
            $response['message'] = "Error preparing the query: " . $conn->error;
        }
    } else {
        $response['status'] = 3;
        $response['message'] = "Invalid QR data.";
    }
}

// Close the connection
$conn->close();

// Output the response as JSON
echo json_encode($response);
