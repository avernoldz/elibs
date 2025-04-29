<?php
// Include database connection
include 'connection.php';

$sql = "SELECT br.request_id, br.request_date, br.status, 
               COALESCE(br.expected_pickup_date, '') AS expected_pickup_date, 
               COALESCE(br.due_date, '') AS due_date, 
               b.title AS book_title, b.author, b.isbn, b.publisher, 
               b.publication_year, b.edition, b.quantity, b.bookshelf_code,
               s.name AS student_name
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Book Request History</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #444; text-align: left; }
        th { background-color: #f0f0f0; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 40px;
        }
        .header img {
            height: 80px;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
        }
        .header-text h1 {
            font-family: 'Old English Text MT', serif;
            font-size: 22px;
            margin: 0;
        }
        .header-text p {
            margin: 2px 0;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Left Logo">
    <div class="header-text">
        <p>Republic of the Philippines</p>
        <h1>Pila Senior High School</h1>
        <p>Province of Laguna</p>
    </div>
    <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Right Logo">
</div>

<h2>Book Request History</h2>

<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Book Title</th>
            <th>Request ID</th>
            <th>Request Date</th>
            <th>Pickup Date</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['student_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['book_title']) . '</td>';
                echo '<td>' . htmlspecialchars($row['request_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['request_date']) . '</td>';
                echo '<td>' . ($row['expected_pickup_date'] ?: 'Not Set') . '</td>';
                echo '<td>' . ($row['due_date'] ?: 'Not Set') . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="7">No book requests found.</td></tr>';
        }
        ?>
    </tbody>
</table>
</body>
</html>

<?php $conn->close(); ?>
