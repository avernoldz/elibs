<?php
include 'connection.php'; // Include database connection

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=books_list.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch data from the books table (excluding book_image_path)
$result = $conn->query("SELECT book_id, title, author, isbn, publisher, publication_year, edition, quantity, bookshelf_code FROM books");

// Output column headers
echo "Book ID\tTitle\tAuthor\tISBN\tPublisher\tPublication Year\tEdition\tQuantity\tBookshelf Code\n";

// Output data rows
while ($row = $result->fetch_assoc()) {
    echo "{$row['book_id']}\t{$row['title']}\t{$row['author']}\t{$row['isbn']}\t{$row['publisher']}\t{$row['publication_year']}\t{$row['edition']}\t{$row['quantity']}\t{$row['bookshelf_code']}\n";
}

$conn->close();
?>
