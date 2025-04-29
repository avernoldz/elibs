<?php
include 'connection.php';

$request_id = $_POST['request_id'];
$sql = "SELECT expected_pickup_date FROM book_requests WHERE request_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode($row);
?>
