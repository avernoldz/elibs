<?php
require 'connection.php';
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set Header
$headers = ['ID', 'Title', 'Author', 'ISBN', 'Publisher', 'Year', 'Edition', 'Quantity', 'Shelf'];
$sheet->fromArray($headers, NULL, 'A1');

// Fill Data
$rowIndex = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->fromArray([
        $row['book_id'],
        $row['title'],
        $row['author'],
        $row['isbn'],
        $row['publisher'],
        $row['publication_year'],
        $row['edition'],
        $row['quantity'],
        $row['bookshelf_code']
    ], NULL, "A{$rowIndex}");
    $rowIndex++;
}

// Output to browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Books_Catalog.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
