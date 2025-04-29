<?php
include 'connection.php';

$book_id = $_POST['book_id'];

// Validate book ID or QR code
// ...

// Update book status in the database
$sql = "UPDATE book_requests SET status = 'Returned', return_date = NOW() WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $book_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(array('success' => true, 'message' => 'Book returned successfully.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to return book.'));
}

$stmt->close();
$conn->close();