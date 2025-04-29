<?php
// get_selected_theses.php
include 'connection.php';

$search_query = $_GET['search'] ?? '';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$theses_per_page = 8;
$offset = ($current_page - 1) * $theses_per_page;

if (!empty($search_query)) {
    $query = "SELECT title, author, advisor, strand, completion_year, bookshelf_code, availability 
              FROM thesis 
              WHERE title LIKE ? 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("sii", $search_param, $offset, $theses_per_page);
} else {
    $query = "SELECT title, author, advisor, strand, completion_year, bookshelf_code, availability 
              FROM thesis 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $theses_per_page);
}

$stmt->execute();
$result = $stmt->get_result();
?>


    <title>Print Theses</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #444; text-align: left; }
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
    </style>


    <div class="header">
        <img src="assets\images\PSHS_LOGO-removebg-preview.png" alt="Left Logo">
        <div class="header-text">
            <p>Republic of the Philippines</p>
            <h1>Pila Senior High School</h1>
            <p>Province of Laguna</p>
        </div>
        <img src="assets\images\Bagong-Pilipinas-Logo-.png" alt="Right Logo">
    </div>

    <h2>Thesis List</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author(s)</th>
                <th>Advisor</th>
                <th>Strand</th>
                <th>Year</th>
                <th>Library Code</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows === 0) {
                echo "<tr><td colspan='6'>No thesis data found.</td></tr>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['advisor']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['strand']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['completion_year']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bookshelf_code']) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>


<?php
$conn->close();
?>
