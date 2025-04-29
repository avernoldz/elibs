<?php
include 'connection.php';

if (isset($_GET['book_id'])) {
    $bookId = intval($_GET['book_id']);
    $stmt = $conn->prepare("UPDATE books SET is_archived = 1 WHERE book_id = ?");
    $stmt->bind_param('i', $bookId);

    if ($stmt->execute()) {
        header("Location: admin_bookcatalog.php?msg=archived");
    } else {
        echo "Error archiving book.";
    }
}
?>
