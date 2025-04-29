<?php
// Include database connection and QR code library
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Check if book IDs are provided
if (isset($_GET['ids'])) {
    $student_ids = explode(',', $_GET['ids']);

    // Fetch selected books
    $query = "SELECT 
                    s.lrn,
                    s.student_id,
                    s.name,
                    s.grade_level,
                    COUNT(br.book_id) AS total_borrowed,
                    SUM(CASE WHEN br.is_returned = 1 THEN 1 ELSE 0 END) AS total_returned,
                    SUM(CASE WHEN br.is_overdue = 1 THEN 1 ELSE 0 END) AS total_overdue
                FROM 
                    students s
                LEFT JOIN 
                    book_requests br ON br.student_id = s.student_id
                WHERE 
                    br.student_id IN (" . implode(',', array_map('intval', $student_ids)) . ")
                GROUP BY 
                    s.student_id ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<h1 class="text-center">Student Lists</h1>';
        echo '<table border="1" cellpadding="10" cellspacing="0" width="100%" style="text-align:left;">';
        echo '<tr>
                <th>LRN</th>
                <th>Student Name</th>
                <th>Grade Level</th>
                <th>Borrowed Books</th>
                <th>Returned Books</th>
                <th>Overdued Books</th>
              </tr>';

        // Loop through each book and display details along with QR code
        while ($row = $result->fetch_assoc()) {

            echo "<tr>
                    <td>" . htmlspecialchars($row['lrn']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['grade_level']) . "</td>
                    <td>" . htmlspecialchars($row['total_borrowed']) . "</td>
                    <td>" . htmlspecialchars($row['total_returned']) . "</td>
                    <td>" . htmlspecialchars($row['total_overdue']) . "</td>
                  </tr>";
        }
        echo '</table>';
    } else {
        echo 'No students found.';
    }
}
$conn->close();
