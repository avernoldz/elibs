<?php
include 'connection.php';

if (isset($_GET['book_id']) && isset($_GET['action'])) {
    $book_id = $_GET['book_id'];
    $action = $_GET['action'];

    // Set availability based on action
    $availability = ($action === 'activate') ? 1 : 0;

    // Update the availability in the database
    $stmt = $conn->prepare("UPDATE books SET availability = ? WHERE book_id = ?");
    $stmt->bind_param("ii", $availability, $book_id);

    if ($stmt->execute()) {
        header("Location: admin_bookcatalog.php?status=success");
    } else {
        header("Location: admin_bookcatalog.php?status=error");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin_bookcatalog.php?status=invalid_request");
}
?>
