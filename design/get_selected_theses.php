<?php
// Include your database connection and vendor autoload
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // Load necessary dependencies

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

if (isset($_GET['ids'])) {
    $thesis_ids = explode(',', $_GET['ids']);
    
    // Prepare the SQL query to fetch selected theses
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code 
              FROM thesis WHERE id IN (" . implode(',', array_map('intval', $thesis_ids)) . ")";
    $result = $conn->query($query);

    // Check if results were fetched
    if ($result->num_rows > 0) {
        echo '<h1>Theses Lists</h1>';
        
        // Start the table
        echo '<table border="1" cellpadding="10" cellspacing="0" width="100%" style="text-align:left;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Title</th>';
        echo '<th>Author</th>';
        echo '<th>Advisor</th>';
        echo '<th>Year</th>';
        echo '<th>Library Code</th>';
        echo '<th>Strand</th>';
        echo '<th>QR Code</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        // Loop through each thesis and generate its QR code
        while ($row = $result->fetch_assoc()) {
            // Generate QR code with thesis information (including library code and strand)
            $qrCodeText = "Title: {$row['title']}\nAuthor: {$row['author']}\nAdvisor: {$row['advisor']}\n"
                        . "Year: {$row['completion_year']}\nLibrary Code: {$row['bookshelf_code']}\nStrand: {$row['strand']}";

            // Create a new QR code
            $qrCode = new QrCode($qrCodeText);
            $writer = new SvgWriter();
            $qrCodeImage = $writer->write($qrCode);

            // Display each thesis as a table row with its QR code
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
            echo '<td>' . htmlspecialchars($row['author']) . '</td>';
            echo '<td>' . htmlspecialchars($row['advisor']) . '</td>';
            echo '<td>' . htmlspecialchars($row['completion_year']) . '</td>';
            echo '<td>' . htmlspecialchars($row['bookshelf_code']) . '</td>';
            echo '<td>' . htmlspecialchars($row['strand']) . '</td>';
            // Display the QR code with the specified size (e.g., 1 inch by 1 inch)
            echo '<td><img src="data:image/svg+xml;base64,' . base64_encode($qrCodeImage->getString()) . '" alt="QR Code" style="width: 75px; height: 75px;" /></td>';
            echo '</tr>';
        }

        // End the table
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No theses found for the selected IDs.</p>';
    }

    // Close the database connection
    $conn->close();
}
?>
