<?php
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

header('Content-Type: image/png');

if (!isset($_GET['book_id'])) {
    http_response_code(400);
    echo 'Missing book_id';
    exit;
}

$bookId = $_GET['book_id'];

$qr = QrCode::create($bookId)->setSize(300);
$writer = new PngWriter();
$result = $writer->write($qr);

echo $result->getString();
exit;
