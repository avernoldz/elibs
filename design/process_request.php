<?php
// Include the database connection
include 'connection.php';

// Ensure it's a POST request and required parameters exist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = intval($_POST['request_id']); // Safely cast to integer
    $action = strtolower(trim($_POST['action'])); // Normalize the action

    $response = ['success' => false]; // Default response

    // Approve or Reject a request
    if (in_array($action, ['approve', 'reject'])) {
        // Map action to valid status values
        $new_status = $action === 'approve' ? 'Approved' : 'Rejected';

        // Update the status
        $sql = "UPDATE book_requests SET status = ? WHERE request_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $new_status, $request_id);
            if ($stmt->execute()) {
                $response['success'] = $stmt->affected_rows > 0;
                $response['message'] = $response['success']
                    ? "Request successfully {$new_status}."
                    : "No changes made. It may already be {$new_status} or not found.";
            } else {
                $response['message'] = 'Failed to execute update.';
            }
            $stmt->close();
        } else {
            $response['message'] = 'Failed to prepare update statement.';
        }

    }
    // Delete a request
    elseif ($action === 'delete') {
        $sql = "DELETE FROM book_requests WHERE request_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $request_id);
            if ($stmt->execute()) {
                $response['success'] = $stmt->affected_rows > 0;
                $response['message'] = $response['success']
                    ? 'Request deleted successfully.'
                    : 'No matching request found to delete.';
            } else {
                $response['message'] = 'Failed to execute delete.';
            }
            $stmt->close();
        } else {
            $response['message'] = 'Failed to prepare delete statement.';
        }

    }
    // Unknown or invalid action
    else {
        $response['message'] = 'Invalid action specified.';
    }

    $conn->close();
    echo json_encode($response);

} else {
    // Invalid request
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. Missing required parameters.'
    ]);
}
