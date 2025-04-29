<?php
include 'connection.php';

header('Content-Type: application/json');

$book_id = $_POST['book_id'];
$title = $_POST['title'];
$author = $_POST['author'];
$isbn = $_POST['isbn'];
$publisher = $_POST['publisher'];
$publication_year = $_POST['publication_year'];
$edition = $_POST['edition'];
$quantity = $_POST['quantity'];
$bookshelf_code = $_POST['bookshelf_code'];

$sql = "UPDATE books SET title=?, author=?, isbn=?, publisher=?, publication_year=?, edition=?, quantity=?, bookshelf_code=? WHERE book_id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssssssisi", $title, $author, $isbn, $publisher, $publication_year, $edition, $quantity, $bookshelf_code, $book_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Book updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update book.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare SQL statement.']);
}

$conn->close();
?>
