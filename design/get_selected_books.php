<?php
require 'connection.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

if (isset($_GET['export']) && $_GET['export'] === 'doc') {
    $sql = "SELECT * FROM books";
    $result = $conn->query($sql);

    $phpWord = new PhpWord();
    $section = $phpWord->addSection();

    $section->addTitle("Books Catalog", 1);
    $table = $section->addTable();

    // Header Row
    $headers = ['ID', 'Title', 'Author', 'ISBN', 'Publisher', 'Publication Year', 'Edition', 'Quantity', 'Shelf'];
    $table->addRow();
    foreach ($headers as $header) {
        $table->addCell(2000)->addText($header);
    }

    // Data Rows
    while ($row = $result->fetch_assoc()) {
        $table->addRow();
        $table->addCell(2000)->addText($row['book_id']);
        $table->addCell(2000)->addText($row['title']);
        $table->addCell(2000)->addText($row['author']);
        $table->addCell(2000)->addText($row['isbn']);
        $table->addCell(2000)->addText($row['publisher']);
        $table->addCell(2000)->addText($row['publication_year']);
        $table->addCell(2000)->addText($row['edition']);
        $table->addCell(2000)->addText($row['quantity']);
        $table->addCell(2000)->addText($row['bookshelf_code']);
    }

    // Output DOCX
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="Books_Catalog.docx"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    $temp_file = tempnam(sys_get_temp_dir(), 'word');
    IOFactory::createWriter($phpWord, 'Word2007')->save($temp_file);
    readfile($temp_file);
    unlink($temp_file);
    exit;
} else {
    echo "Invalid export request.";
}
?>
