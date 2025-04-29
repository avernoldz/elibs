<?php
include 'connection.php';

$query = $_GET['query'] ?? '';

if (!empty($query)) {
    $stmt = $conn->prepare("SELECT id, name, lrn FROM students WHERE name LIKE ? OR lrn LIKE ? LIMIT 5");
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    echo json_encode($students);
    $stmt->close();
}

$conn->close();
?>
