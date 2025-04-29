<?php
// File: archive_student.php

include 'connection.php';

if (isset($_GET['id'])) {
    $lrn = $_GET['id'];

    // Optional: Validate LRN format (assuming numeric)
    if (!preg_match('/^[0-9]+$/', $lrn)) {
        header("Location: admin_studentlist.php?error=Invalid LRN");
        exit();
    }

    // Archive student
    $query = "UPDATE students SET archived = 1 WHERE lrn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $lrn);

    if ($stmt->execute()) {
        header("Location: admin_studentlist.php?success=Student archived successfully");
    } else {
        header("Location: admin_studentlist.php?error=Failed to archive student");
    }

    $stmt->close();
} else {
    header("Location: admin_studentlist.php?error=No LRN specified");
}
$conn->close();
?>
