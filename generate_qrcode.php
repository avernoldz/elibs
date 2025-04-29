<?php
require __DIR__ . '/../vendor/autoload.php'; // Load necessary dependencies

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

// Create a new QR code
$qrCode = new QrCode('This is a test QR code');
$writer = new SvgWriter();

// Generate the QR code as an SVG image
$qrCodeImage = $writer->write($qrCode);

// Display the SVG image inline
echo "<img src='data:image/svg+xml;base64," . base64_encode($qrCodeImage->getString()) . "' />";
?>

