<?php
include 'connection.php';

$isWordExport = isset($_GET['export']) && $_GET['export'] === 'doc';

if ($isWordExport) {
    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment;Filename=all_books.doc");
}

header("Content-Type: text/html; charset=utf-8");

echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>All Books</title>';
echo '<style>@page { size: landscape; }</style></head>';
echo '<body style="font-family: Arial, sans-serif; margin: 40px;">';
echo '<h1 style="text-align: center; color: #2c3e50;">Library Book Report</h1>';

$query = "SELECT book_id, title, author, isbn, publisher, publication_year, edition, quantity, bookshelf_code FROM books";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0" width="100%" style="border-collapse: collapse; margin-top: 30px;">';
    echo '<tr style="background-color: #34495e; color: #ecf0f1;">'
       . '<th>Book ID</th>'
       . '<th>Title</th>'
       . '<th>Author</th>'
       . '<th>ISBN</th>'
       . '<th>Publisher</th>'
       . '<th>Year</th>'
       . '<th>Edition</th>'
       . '<th>Quantity</th>'
       . '<th>Shelf</th>'
       . '</tr>';

    $rowIndex = 0;
    while ($row = $result->fetch_assoc()) {
        $rowColor = ($rowIndex++ % 2 == 0) ? '#f9f9f9' : '#ffffff';
        echo '<tr style="background-color: ' . $rowColor . ';">';
        echo '<td>' . htmlspecialchars($row['book_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
        echo '<td>' . htmlspecialchars($row['author']) . '</td>';
        echo '<td>' . htmlspecialchars($row['isbn']) . '</td>';
        echo '<td>' . htmlspecialchars($row['publisher']) . '</td>';
        echo '<td>' . htmlspecialchars($row['publication_year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['edition']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['bookshelf_code']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p style="color: red; text-align: center;">No books found.</p>';
}

$conn->close();
echo '</body></html>';
