<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action == 'set_pickup_date') {
        $pickupDate = $_POST['pickup_date'];
        $sql = "UPDATE book_requests SET expected_pickup_date = ? WHERE request_id = ?";
    } elseif ($action == 'set_due_date') {
        $dueDate = $_POST['due_date'];
        $sql = "UPDATE book_requests SET due_date = ? WHERE request_id = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $pickupDate ?? $dueDate, $requestId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }

    $stmt->close();
}
?>
