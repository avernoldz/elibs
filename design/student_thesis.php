<?php
include 'connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$search_query = trim($_GET['search'] ?? '');
$filter = $_GET['filter'] ?? 'title';
$selected_year = $_GET['year'] ?? '';

$allowed_filters = ['title', 'author', 'advisor', 'strand'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'title';
}

$theses_per_page = 10;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $theses_per_page;

// Shared WHERE clause logic
$where_clauses = [];
$params = [];
$types = '';

if (!empty($search_query)) {
    $where_clauses[] = "$filter LIKE ?";
    $params[] = '%' . $search_query . '%';
    $types .= 's';
}
if (!empty($selected_year) && is_numeric($selected_year)) {
    $where_clauses[] = "completion_year = ?";
    $params[] = $selected_year;
    $types .= 'i';
}
$where_sql = !empty($where_clauses) ? ' WHERE ' . implode(' AND ', $where_clauses) : '';

// COUNT total
$count_sql = "SELECT COUNT(*) FROM thesis $where_sql";
$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($total_theses);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_theses / $theses_per_page);

// Fetch paginated data
$data_sql = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability 
             FROM thesis $where_sql LIMIT ?, ?";
$stmt = $conn->prepare($data_sql);

$params[] = $offset;
$params[] = $theses_per_page;
$types .= 'ii';
$stmt->bind_param($types, ...$params);

$stmt->execute();
$result = $stmt->get_result();

// Function to render pagination
function renderPagination($total_pages, $current_page, $search_query = '', $filter = 'title', $selected_year = '') {
    $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';
    $filter_param = '&filter=' . urlencode($filter);
    $year_param = !empty($selected_year) ? '&year=' . urlencode($selected_year) : '';

    echo '<nav><ul class="pagination justify-content-center mt-4">';

    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="student_thesiscatalog.php?page=' . ($current_page - 1) . $search_param . $filter_param . $year_param . '">Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i === $current_page ? ' active' : '';
        echo '<li class="page-item' . $active . '"><a class="page-link" href="student_thesiscatalog.php?page=' . $i . $search_param . $filter_param . $year_param . '">' . $i . '</a></li>';
    }

    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="student_thesiscatalog.php?page=' . ($current_page + 1) . $search_param . $filter_param . $year_param . '">Next</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    echo '</ul></nav>';
}
?>

<!-- HTML Starts -->
<div class="row">
    <div class="col-sm-12 col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Theses</div>
                <div class="search-container d-flex align-items-center">
                    <form action="student_thesiscatalog.php" method="GET" class="d-flex align-items-center">
                        <select class="form-control me-2" name="filter">
                            <option value="title" <?= $filter === 'title' ? 'selected' : '' ?>>Title</option>
                            <option value="author" <?= $filter === 'author' ? 'selected' : '' ?>>Author(s)</option>
                            <option value="advisor" <?= $filter === 'advisor' ? 'selected' : '' ?>>Advisor</option>
                            <option value="strand" <?= $filter === 'strand' ? 'selected' : '' ?>>Strand</option>
                        </select>

                        <select class="form-control me-2" name="year">
                            <option value="">Select Year</option>
                            <?php
                            for ($year = 2020; $year <= date("Y"); $year++) {
                                echo "<option value='$year'" . ($selected_year == $year ? ' selected' : '') . ">$year</option>";
                            }
                            ?>
                        </select>

                        <input type="text" class="form-control" name="search" placeholder="Search Thesis" value="<?= htmlspecialchars($search_query) ?>"> 
                        <button class="btn btn-primary ms-2" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Advisor</th>
                                <th>Strand</th>
                                <th>Year</th>
                                <th>Library Code</th>
                                <th>Availability</th>
                                <th>Abstract</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td><?= htmlspecialchars($row['author']) ?></td>
                                        <td><?= htmlspecialchars($row['advisor']) ?></td>
                                        <td><?= htmlspecialchars($row['strand']) ?></td>
                                        <td><?= htmlspecialchars($row['completion_year']) ?></td>
                                        <td><?= htmlspecialchars($row['bookshelf_code']) ?></td>
                                        <td>
                                            <span class="badge <?= $row['availability'] ? 'shade-green' : 'shade-red' ?>">
                                                <?= $row['availability'] ? 'Available' : 'Unavailable' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['availability']) { ?> 
                                                <a href="view_thesis.php?id=<?= $row['id'] ?>" class="viewRow" data-id="<?= $row['id'] ?>" data-bs-toggle="modal" data-bs-target="#viewRow">
                                                    <i class="bi bi-eye text-green"></i>
                                                </a>
                                            <?php } else { ?> <a>
                                                <i class="bi bi-eye-slash text-red"></i> 
                                            <?php } ?> </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8'>No theses found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php renderPagination($total_pages, $current_page, $search_query, $filter, $selected_year); ?>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>
