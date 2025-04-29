<?php
// Include the database connection
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // Load necessary dependencies
include 'send_sms.php';


// Initialize an array to store the response
$response = array();


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data, handling cases where the user might leave fields empty
    $id = !empty($_POST['id']) ? $_POST['id'] : null;
    $return_date = !empty($_POST['return_date']) ? $_POST['return_date'] : null; // Get return date from POST

    $student_info = "
        SELECT books.isbn, students.phonenumber. students.name FROM book_requests 
            INNER JOIN students ON book_requests.student_id = students.student_id
            INNER JOIN books ON books.book_id = book_requests.book_id
            WHERE book_requests.request_id = ?
    ";
    $stmt = $conn->prepare($student_info);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    $phone_number = $student['phonenumber'];

    if (substr($phone_number, 0, 1) == '0') {
        $to = '+63' . substr($phone_number, 1);  // Replace the leading '0' with '+63'
    } else {
        $to = $phone_number;  // If there's no leading '0', leave the number as is
    }


    $return_date = date('m/d/Y');
    $return_time = date('H:i:s');

    $body = "Hello $student[name], the book with ISBN: $student[isbn] has been successfully returned on $return_date at $return_time. \n\n Thank you for reading!";


    if ($id && $return_date) {
        // SQL query to update the record
        $query = "UPDATE book_requests SET return_date = ?, is_returned = 1 WHERE request_id = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);

        if ($stmt) {
            // Define the types of the parameters
            $types = 'si'; // 's' for string (return_date), 'i' for integer (id)
            $stmt->bind_param($types, $return_date, $id); // Bind the parameters

            // Execute the query
            if ($stmt->execute()) {
                $response['status'] = 1;  // Success status
                $response['message'] = "Record updated successfully.";

                sendSms($to, $body);
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
        $response['status'] = 2;
        $response['message'] = "Invalid or missing data.";
    }
}

// Close the connection
$conn->close();

// Output the response as JSON
echo json_encode($response);
