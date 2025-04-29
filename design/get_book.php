<?php
include 'connection.php';

$bookId = $_GET['book_id'];

$sql = "SELECT * FROM books WHERE book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    echo json_encode($result->fetch_assoc());
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Book not found']);
}

$conn->close();
?>
