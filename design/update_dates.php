<?php
// Include DB connection
include 'connection.php';

// Validate incoming POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'] ?? null;
    $action = $_POST['action'] ?? null;

    // Validate input
    if (!$request_id || !$action) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters.']);
        exit;
    }

    // Handle set pickup date
    if ($action === 'set_pickup_date' && isset($_POST['pickup-date'])) {
        $pickup_date = $_POST['pickup-date'];
        $sql = "UPDATE book_requests SET expected_pickup_date = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $pickup_date, $request_id);
        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => $success]);
        exit;
    }

    // Handle set due date
    if ($action === 'set_due_date' && isset($_POST['due-date'])) {
        $due_date = $_POST['due-date'];
        $sql = "UPDATE book_requests SET due_date = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $due_date, $request_id);
        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(['success' => $success]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid action or missing date.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
