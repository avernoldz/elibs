<?php
// Include database connection and QR code library
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Check if book IDs are provided
if (isset($_GET['ids'])) {
    $book_ids = explode(',', $_GET['ids']);

    // Fetch selected books
    $query = "SELECT book_id, title, author, publisher, bookshelf_code, availability FROM books WHERE book_id IN (" . implode(',', array_map('intval', $book_ids)) . ")";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<h1 class="text-center">Book Lists</h1>';
        echo '<table border="1" cellpadding="10" cellspacing="0" width="100%" style="text-align:left;">';
        echo '<tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Shelf Code</th>
                <th>QR Code</th>
              </tr>';

        // Loop through each book and display details along with QR code
        while ($row = $result->fetch_assoc()) {
            $qrContent = sprintf(
                "Title: %s\nAuthor: %s\nPublisher: %s\nShelf Code: %s",
                $row['title'],
                $row['author'],
                $row['publisher'],
                $row['bookshelf_code'],

            );

            $qrCode = new QrCode($qrContent);
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode);
            $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCodeImage->getString());

            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['author']) . "</td>
                    <td>" . htmlspecialchars($row['publisher']) . "</td>
                    <td>" . htmlspecialchars($row['bookshelf_code']) . "</td>
                    <td><img src='$qrCodeDataUri' alt='QR Code' style='width: 75px; height: 75px;'></td>
                  </tr>";
        }
        echo '</table>';
    } else {
        echo 'No books found.';
    }
}
$conn->close();
