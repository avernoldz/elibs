<?php
// Database connection
include 'connection.php';

if (isset($_GET['lrn'])) {
    $lrn = $_GET['lrn'];

    // Prepare the query to fetch student data
    $stmt = $conn->prepare("SELECT name, birthday, email, grade_level, section FROM students WHERE lrn = ?");
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $stmt->bind_result($name, $birthday, $email, $grade_level, $section);
    
    // Fetch data
    if ($stmt->fetch()) {
        // Return data as JSON
        echo json_encode([
            'error' => false,
            'name' => $name,
            'birthday' => $birthday,
            'email' => $email,
            'grade_level' => $grade_level,
            'section' => $section,
        ]);
    } else {
        echo json_encode(['error' => 'Student not found']);
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
