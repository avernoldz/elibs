<?php
include 'connection.php';

$sql = "SELECT br.request_id, br.request_date, br.due_date, br.return_date, 
        s.name AS student_name, b.title AS book_title
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id
        WHERE br.status = 'Returned'
        ORDER BY br.return_date DESC";

$result = $conn->query($sql);

$books = array();
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$conn->close();

echo json_encode(array('success' => true, 'books' => $books));