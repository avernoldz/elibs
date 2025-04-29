<?php
include 'connection.php';

if (isset($_GET['book_id'])) {
    $book_id = (int) $_GET['book_id'];
    $stmt = $conn->prepare("UPDATE books SET archived = 0 WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute()) {
        header("Location: archived_books.php?restored=1");
        exit;
    } else {
        echo "Error restoring book.";
    }
}
?>
