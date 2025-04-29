<?php
// Include the database connection file
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // Load necessary dependencies
include 'send_sms.php';

// Start the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = (int)$_SESSION['student_id'];

// Validate and retrieve the book_id from the POST request
if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'Invalid request. Please try again.'
    ];
    header("Location: student_bookcatalog.php");
    exit();
}

$book_id = (int)$_POST['book_id'];

// Check the count of pending requests and if the specific book has already been requested
$student_info = "
    SELECT * FROM students WHERE student_id = ?
";
$stmt = $conn->prepare($student_info);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$book_info = "
    SELECT * FROM books WHERE book_id = ?
";
$stmt = $conn->prepare($book_info);
$stmt->bind_param('i', $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

$phone_number = $student['phonenumber'];

if (substr($phone_number, 0, 1) == '0') {
    $to = '+63' . substr($phone_number, 1);  // Replace the leading '0' with '+63'
} else {
    $to = $phone_number;  // If there's no leading '0', leave the number as is
}

$body = "Your book request has been successfully processed. The book with ISBN: $book[isbn] is now reserved for you. \n\n Enjoy your reading!";

// Check the count of pending requests and if the specific book has already been requested
$count_request_sql = "
    SELECT 
        (SELECT COUNT(*) FROM book_requests WHERE student_id = ? AND status = 'pending') AS request_count,
        (SELECT COUNT(*) FROM book_requests WHERE book_id = ? AND student_id = ? AND status = 'pending') AS duplicate_request
";
$stmt = $conn->prepare($count_request_sql);
$stmt->bind_param('iii', $student_id, $book_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['request_count'] >= 5) {
    // If student has reached the request limit
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'You have reached the maximum limit of 5 pending book requests.'
    ];
} elseif ($row['duplicate_request'] > 0) {
    // If the specific book is already requested
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'You have already requested this book. Please wait for the request to be processed.'
    ];
} else {
    // Insert the new book request if within limit and not a duplicate
    $insert_request_sql = "INSERT INTO book_requests (book_id, student_id, request_date, status) VALUES (?, ?, NOW(), 'pending')";
    $stmt_insert = $conn->prepare($insert_request_sql);

    if ($stmt_insert && $stmt_insert->bind_param('ii', $book_id, $student_id) && $stmt_insert->execute()) {

        sendSms($to, $body);

        $_SESSION['request_status'] = [
            'type' => 'success',
            'message' => 'Your request has been submitted successfully.'
        ];
    } else {
        $_SESSION['request_status'] = [
            'type' => 'error',
            'message' => 'There was an error processing your request. Please try again.'
        ];
    }
    $stmt_insert->close();
}

$stmt->close();
$conn->close();

// Redirect to the books page
header("Location: student_bookcatalog.php");
exit();
?>
