<?php
// Include the database connection
include 'connection.php';

// Check if the request method is POST and required data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['action'])) {
    // Get the request ID and action (approve or reject) from POST data
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Ensure the request ID is valid and numeric
    if (!is_numeric($request_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
        exit;
    }

    // Determine the new status based on the action
    if ($action === 'approve') {
        $new_status = 'Approved';
    } elseif ($action === 'reject') {
        $new_status = 'Rejected';
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }

    // Prepare and execute the SQL query to update the request status
    $sql = "UPDATE book_requests SET status = ? WHERE request_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $new_status, $request_id);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            // Check if any rows were affected (i.e., if the request ID exists)
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Request updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No matching request found or already updated']);
            }
        } else {
            // SQL execution failed
            echo json_encode(['success' => false, 'message' => 'Failed to update request. Please try again later.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // SQL preparation failed
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the request.']);
    }

    // Close the database connection
    $conn->close();
} else {
    // Invalid request (missing request_id or action)
    echo json_encode(['success' => false, 'message' => 'Invalid request. Missing parameters.']);
}

?>
