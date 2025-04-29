<?php
include 'connection.php';

$month = $_GET['month'] ?? '';
$grade = $_GET['grade'] ?? '';

$where = [];
if (!empty($month)) {
    $where[] = "MONTH(br.return_date) = " . intval($month);
}
if (!empty($grade)) {
    $where[] = "s.grade_level = " . intval($grade);
}

$whereSql = count($where) ? "WHERE " . implode(' AND ', $where) : '';

$query = "SELECT br.return_date, br.is_returned,
                 b.title AS book_title,
                 s.name AS student_name, s.grade_level
          FROM book_requests br
          JOIN books b ON br.book_id = b.book_id
          JOIN students s ON br.student_id = s.student_id
          $whereSql
          ORDER BY br.return_date DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Returned Book History</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        h2 { text-align: center; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 80px;
        }
        .header-text {
            text-align: center;
            flex-grow: 1;
        }
        .header-text h1 {
            margin: 0;
            font-family: 'Old English Text MT', serif;
            font-size: 24px;
        }
        .header-text p {
            margin: 2px 0;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Logo">
        <div class="header-text">
            <p>Republic of the Philippines</p>
            <h1>Pila Senior High School</h1>
            <p>Province of Laguna</p>
        </div>
        <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Logo">
    </div>

    <h2>Returned Book Records</h2>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Grade Level</th>
                <th>Book Title</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['grade_level']) ?></td>
                        <td><?= htmlspecialchars($row['book_title']) ?></td>
                        <td><?= date('m/d/Y', strtotime($row['return_date'])) ?></td>
                        <td><?= $row['is_returned'] ? 'Returned' : 'Pending' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php $conn->close(); ?>
