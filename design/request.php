<?php
// Include the database connection file
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php';
include 'send_sms.php';

session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = (int)$_SESSION['student_id'];

if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'Invalid request. Please try again.'
    ];
    header("Location: student_bookcatalog.php");
    exit();
}

$book_id = (int)$_POST['book_id'];

$student_info = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($student_info);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$book_info = "SELECT * FROM books WHERE book_id = ?";
$stmt = $conn->prepare($book_info);
$stmt->bind_param('i', $book_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

$phone_number = $student['phonenumber'];
$to = (substr($phone_number, 0, 1) == '0') ? '+63' . substr($phone_number, 1) : $phone_number;

$body = "Your book request has been successfully processed. The book with ISBN: $book[isbn] is now reserved for you. \n\n Enjoy your reading!";

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
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'You have reached the maximum limit of 5 pending book requests.'
    ];
} elseif ($row['duplicate_request'] > 0) {
    $_SESSION['request_status'] = [
        'type' => 'error',
        'message' => 'You have already requested this book. Please wait for the request to be processed.'
    ];
} else {
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

header("Location: student_bookcatalog.php");
exit();
?>

