<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $dueDate = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE book_requests SET due_date = ? WHERE request_id = ? AND status = 'Pending'");
    $stmt->bind_param('si', $dueDate, $requestId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}
$conn->close();
?>
