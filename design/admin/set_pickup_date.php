<?php
// Include the database connection file
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the request ID and expected pickup date from POST data
    $request_id = $_POST['request_id'];
    $expected_pickup_date = $_POST['expected_pickup_date'];

    // Validate the input
    if (!empty($expected_pickup_date) && !empty($request_id)) {
        // Update the expected pickup date in the database as text
        $sql = "UPDATE book_requests SET expected_pickup_date = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $expected_pickup_date, $request_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }

    $conn->close();
}
