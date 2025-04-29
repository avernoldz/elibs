<?php
require __DIR__ . '/fpdf/fpdf.php'; // Adjusted to the correct path
include 'connection.php'; // Include database connection

if (isset($_GET['ids'])) {
    $thesis_ids = explode(',', $_GET['ids']);
    
    // Query to fetch selected theses
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code 
            FROM thesis 
            WHERE id IN (" . implode(',', array_map('intval', $thesis_ids)) . ")";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, 'Selected Theses', 0, 1, 'C');
        $pdf->Ln(10);

        while ($row = $result->fetch_assoc()) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, 'Title: ' . $row['title'], 0, 1);
            
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(190, 10, 'Author: ' . $row['author'], 0, 1);
            $pdf->Cell(190, 10, 'Advisor: ' . $row['advisor'], 0, 1);
            $pdf->Cell(190, 10, 'Strand: ' . $row['strand'], 0, 1);
            $pdf->Cell(190, 10, 'Year of Completion: ' . $row['completion_year'], 0, 1);
            $pdf->Cell(190, 10, 'Library Code: ' . $row['bookshelf_code'], 0, 1);
            $pdf->Ln(10); // Add some space between theses
        }

        // Output PDF for download
        $pdf->Output('D', 'Selected_Theses.pdf');
    } else {
        echo 'No theses found.';
    }
} else {
    echo 'No thesis IDs provided.';
}
?>
