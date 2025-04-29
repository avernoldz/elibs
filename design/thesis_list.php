<?php
// Database connection
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

$search_query = '';
$theses_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $theses_per_page;

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

function renderPagination($total_pages, $current_page, $search_query = '') {
    $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';

    echo '<div class="pagination justify-content-center mt-4">';

    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
        }
    }

    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    echo '</div>';
}

if (!empty($search_query)) {
    $query = "SELECT COUNT(*) FROM thesis WHERE title LIKE ?";
    $stmt = $conn->prepare($query);
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("s", $search_param);
} else {
    $query = "SELECT COUNT(*) FROM thesis";
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$stmt->bind_result($total_theses);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_theses / $theses_per_page);

if (!empty($search_query)) {
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability FROM thesis WHERE title LIKE ? LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $search_param, $offset, $theses_per_page);
} else {
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability FROM thesis LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $theses_per_page);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Styles & Search Tools -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="row">
    <div class="col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Theses</div>
                <div class="search-container">
                    <button type="button" class="btn btn-success btn-icon me-1" onclick="window.location.href='admin_addthesis.php'" title="Add New Thesis">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-icon me-1" onclick="printAllThesisData()" title="Print">
                        <i class="bi bi-printer"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-icon me-1" onclick="exportThesisToPDF()" title="Export to PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </button>

                    <div class="input-group">
                        <form action="admin_thesiscatalog.php" method="GET">
                            <input type="text" class="form-control" name="search" placeholder="Search Thesis Title" value="<?php echo htmlspecialchars($search_query); ?>">
                            <button class="btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0 thesis-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Advisor</th>
                                <th>Strand</th>
                                <th>Year</th>
                                <th>Library Code</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                                        <td><?php echo htmlspecialchars($row['advisor']); ?></td>
                                        <td><?php echo htmlspecialchars($row['strand']); ?></td>
                                        <td><?php echo htmlspecialchars($row['completion_year']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookshelf_code']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $row['availability'] ? 'shade-green' : 'shade-red'; ?> min-70">
                                                <?php echo $row['availability'] ? 'Available' : 'Unavailable'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <a href="view_thesis.php" class="viewRow" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewRow">
                                                    <i class="bi bi-eye text-green"></i>
                                                </a>
                                                <a href="#" class="editThesis" data-id="<?php echo $row['id']; ?>">
                                                    <i class="bi bi-pencil-square text-blue"></i>
                                                </a>
                                                <a href="#" class="archiveThesis" data-id="<?php echo $row['id']; ?>" title="Archive">
                                                    <i class="bi bi-archive text-orange"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='9'>No theses found.</td></tr>";
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.archiveThesis').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const thesisId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Archive Thesis',
                text: 'Are you sure you want to archive this thesis? It will be moved from the active catalog.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, archive it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`archive_thesis.php?id=${thesisId}`)
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Archived!',
                                    text: 'Thesis has been successfully archived.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                throw new Error();
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Failed to archive the thesis.'
                            });
                        });
                }
            });
        });
    });

    document.querySelectorAll('.editThesis').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const thesisId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Edit Thesis',
                text: 'Are you sure you want to edit this thesis?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `admin_editthesis.php?id=${thesisId}`;
                }
            });
        });
    });

    window.printAllThesisData = function () {
    const searchParam = new URLSearchParams(window.location.search).get('search') || '';
    const pageParam = new URLSearchParams(window.location.search).get('page') || '1';
    const printUrl = `get_selected_theses.php?search=${encodeURIComponent(searchParam)}&page=${pageParam}`;

    fetch(printUrl)
        .then(response => {
            if (!response.ok) throw new Error("Failed to load thesis data.");
            return response.text();
        })
        .then(data => {
            const printWindow = window.open('', 'width=800,height=600');
            if (!printWindow) {
                Swal.fire({
                    icon: 'error',
                    title: 'Popup Blocked',
                    text: 'Please allow popups for this site to enable printing.'
                });
                return;
            }

            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Theses</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; }
                            .actions, .btn, .bi { display: none !important; } /* Hide buttons & icons */
                        </style>
                    </head>
                    <body>
                        ${data}
                        <script>
                            window.onload = function () {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while loading theses data for printing.',
            });
        });
}


    window.exportThesisToExcel = function () {
        const table = document.querySelector(".table");
        if (!table) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Table not found.',
            });
            return;
        }

        const wb = XLSX.utils.table_to_book(table, { sheet: "Thesis List" });
        XLSX.writeFile(wb, "thesis_list.xlsx");
    }
});
</script>

</body>
</html>
<?php $conn->close(); ?>
