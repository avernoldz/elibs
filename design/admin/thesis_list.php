<?php
// Database connection
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // For QR code library
require_once __DIR__ . '/../vendor/tecnickcom/tcpdf/tcpdf.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Initialize search query
$search_query = '';

// Number of theses to display per page
$theses_per_page = 8;

// Get the current page from the URL, if not set, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record for the current page
$offset = ($current_page - 1) * $theses_per_page;

// Check if search is performed
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Function to generate pagination links
function renderPagination($total_pages, $current_page, $search_query = '') {
    $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';

    echo '<div class="pagination justify-content-center mt-4">';

    // Previous button
    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="admin_thesiscatalog.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    echo '</div>';
}

// Prepare the SQL query based on whether a search term is provided
if (!empty($search_query)) {
    // Count total theses for pagination
    $query = "SELECT COUNT(*) FROM thesis WHERE title LIKE ?";
    $stmt = $conn->prepare($query);
    $search_param = '%' . $search_query . '%';
    $stmt->bind_param("s", $search_param);
} else {
    $query = "SELECT COUNT(*) FROM thesis";
    $stmt = $conn->prepare($query);
}

// Execute the count query
$stmt->execute();
$stmt->bind_result($total_theses);
$stmt->fetch();
$stmt->close();

// Calculate total pages
$total_pages = ceil($total_theses / $theses_per_page);

// Fetch theses for the current page
if (!empty($search_query)) {
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability 
              FROM thesis 
              WHERE title LIKE ? 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $search_param, $offset, $theses_per_page);
} else {
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability 
              FROM thesis 
              LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $theses_per_page);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();
?>


    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


<div class="row">
    <div class="col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Theses</div>
                <div class="search-container">
                    <!-- Add Thesis Button -->
                    <button type="button" class="btn btn-success btn-icon me-1" onclick="window.location.href='admin_addthesis.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Thesis">
                        <i class="bi bi-plus-circle"></i> 
                    </button>
                    <!-- Print Button -->
                    <button type="button" class="btn btn-primary btn-icon me-1" onclick="printAllThesisData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                        <i class="bi bi-printer"></i>
                    </button>
                    <!-- Export to Excel Button -->
                    <button type="button" class="btn btn-info btn-icon me-1" onclick="exportThesisToExcel()" data-bs-toggle="tooltip" data-bs-placement="top" title="Export to Excel">
                        <i class="bi bi-file-earmark-excel"></i>
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

                                            <!-- Edit Button -->
                                            <a href="admin_editthesis.php?id=<?php echo $row['id']; ?>" class="editRow">
                                                <i class="bi bi-pencil-square text-blue"></i>
                                            </a>

                                            <?php if ($row['availability'] == 1) { ?> <a>
                                                <button type="button" class="btn btn-link deactivateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="deactivate" data-id="<?php echo $row['id']; ?>">
                                                    <i class="bi bi-x-circle text-red"></i>
                                                </button></a>
                                            <?php } else { ?> <a>
                                                <button type="button" class="btn btn-link activateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="activate" data-id="<?php echo $row['id']; ?>">
                                                    <i class="bi bi-check-circle text-green"></i>
                                                </button></a>
                                            <?php } ?>
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

                <!-- Render Pagination -->
                <?php renderPagination($total_pages, $current_page, $search_query); ?>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    // SweetAlert2 for confirmation
    document.querySelectorAll('.deactivateRow, .activateRow').forEach(button => {
        button.addEventListener('click', function () {
            const actionType = this.getAttribute('data-action');
            const thesisId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to ${actionType} this thesis.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `thesistoggle_availability.php?id=${thesisId}&action=${actionType}`;
                }
            });
        });
    });

    // Print function with SweetAlert2
    function printAllThesisData() {
        let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
        if (selectedTheses.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Theses Selected',
                text: 'Please select at least one thesis to print.',
            });
            return;
        }

        const thesesParam = selectedTheses.join(',');
        const printUrl = "get_selected_theses.php?ids=" + thesesParam;
        
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Theses</title></head><body>');
        
        fetch(printUrl)
            .then(response => response.text())
            .then(data => {
                printWindow.document.write(data);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while fetching theses data.',
                });
            });
    }

    // Export to Excel function
    function exportThesisToExcel() {
        let table = document.querySelector(".table");
        if (!table) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Table not found.',
            });
            return;
        }

        let wb = XLSX.utils.table_to_book(table, {sheet: "Thesis List"});
        XLSX.writeFile(wb, "thesis_list.xlsx");
    }
</script>


</body>
</html>
<?php
// Close the connection
$conn->close();
?>