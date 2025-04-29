<?php
// File: admin_archivedstudents.php

include 'connection.php';

$search_query = '';
$students_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $students_per_page;

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

function renderPagination($total_pages, $current_page, $search_query = '') {
    $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';
    echo '<div class="pagination justify-content-center mt-4">';

    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="admin_archivedstudents.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="admin_archivedstudents.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
        }
    }

    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="admin_archivedstudents.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    echo '</div>';
}

if (!empty($search_query)) {
    $query = "SELECT COUNT(*) FROM students WHERE archived = 1 AND (name LIKE ? OR lrn LIKE ?)";
    $stmt = $conn->prepare($query);
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("ss", $search_param, $search_param);
} else {
    $query = "SELECT COUNT(*) FROM students WHERE archived = 1";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$stmt->bind_result($total_students);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_students / $students_per_page);

if (!empty($search_query)) {
    $query = "SELECT lrn, name, birthday, email, grade_level, section, status FROM students WHERE archived = 1 AND (name LIKE ? OR lrn LIKE ?) LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $search_param, $search_param, $offset, $students_per_page);
} else {
    $query = "SELECT lrn, name, birthday, email, grade_level, section, status FROM students WHERE archived = 1 LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $students_per_page);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title">Archived Students</div>
                <div class="d-flex align-items-center">
                    <div class="input-group">
                        <form action="admin_archivedstudents.php" method="GET">
                            <input type="text" class="form-control" name="search" placeholder="Search Name or LRN" value="<?php echo htmlspecialchars($search_query); ?>">
                            <button class="btn" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Date of Birth</th>
                                <th>Email</th>
                                <th>Grade</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['lrn']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['birthday']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['grade_level']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['section']) . '</td>';
                                    echo '<td>' . ($row['status'] ? '<span class="badge shade-green">Active</span>' : '<span class="badge shade-red">Blocked</span>') . '</td>';
                                    echo '<td>';
                                    echo '<a href="restore_student.php?id=' . $row['lrn'] . '" class="btn btn-success btn-sm">Restore</a> ';
                                    echo '<a href="delete_student.php?id=' . $row['lrn'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8">No archived students found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php renderPagination($total_pages, $current_page, $search_query); ?>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
?>
