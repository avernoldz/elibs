<?php
include 'connection.php';

if (isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';
    
    $sql = "SELECT student_id, name FROM students WHERE name LIKE ? OR student_id LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($students);
}

$conn->close();
?>
