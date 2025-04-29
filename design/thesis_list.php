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
$theses_per_page = 4;

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
        <!-- Download PDF Button -->
            <button type="button" class="btn btn-danger btn-icon me-1" onclick="downloadSelectedThesesPDF()" data-bs-toggle="tooltip" data-bs-placement="top" title="Download PDF">
                <i class="bi bi-file-earmark-pdf"></i>
            </button>


        <!-- Print Button -->
        <button type="button" class="btn btn-primary btn-icon me-1" onclick="printAllThesisData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
            <i class="bi bi-printer"></i>
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
                                <th>Select</th> <!-- New column header for checkboxes -->
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Advisor</th>
                                <th>Strand</th>
                                <th>Year</th>
                                <th>Library Code</th>
                                <th>Availability</th> <!-- Added new column header -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if there are any results
                            if ($result->num_rows > 0) {
                                // Loop through each row in the result set
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td>
                                            <!-- New Checkbox for Thesis Selection -->
                                            <input type="checkbox" name="selectedTheses[]" class="selectThesisCheckbox" value="<?php echo $row['id']; ?>">
                                        </td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td>
                                            <div class="media-box-body">
                                                <div class="text-truncate"><?php echo htmlspecialchars($row['author']); ?></div>
                                                <p>Thesis ID:<?php echo htmlspecialchars($row['id']); ?></p>
                                            </div>
                                        </td>
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
                                                    <?php if ($row['availability'] == 1) { ?>
                                                        <!-- Button for marking as unavailable -->
                                                         <a href=" 
                                                        <button type="button" class="btn btn-link deactivateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="deactivate" data-id="<?php echo $row['id']; ?>">
                                                            <i class="bi bi-x-circle text-red"></i>
                                                        </button>
                                                        </a>
                                                    <?php } else { ?>
                                                        <!-- Button for marking as available -->
                                                         <a href=" 
                                                        <button type="button" class="btn btn-link activateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="activate" data-id="<?php echo $row['id']; ?>">
                                                            <i class="bi bi-check-circle text-green"></i>
                                                        </button>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </td>

                                        
                                        
                                    </tr>
                                    <?php
                                }
                            } else {
                                // If no theses are found
                                echo "<tr><td colspan='8'>No theses found.</td></tr>"; // Updated colspan for the new column
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
<?php
// Close the connection
$conn->close();
?>
<!-- Modal for confirming action -->
<div class="modal fade" id="modalDark" tabindex="-1" aria-labelledby="modalDarkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDarkLabel">Change Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the availability status of this thesis?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Capture action from the buttons and handle confirmation
    let actionType = '';
    let thesisId = '';
    
    // Attach click listeners to deactivateRow and activateRow buttons for theses
    document.querySelectorAll('.deactivateRow, .activateRow').forEach(button => {
        button.addEventListener('click', function () {
            actionType = this.getAttribute('data-action');
            thesisId = this.getAttribute('data-id');
        });
    });

    // On confirm button click, redirect to the appropriate action (activate/deactivate) for thesis
    document.getElementById('confirmAction').addEventListener('click', function () {
        window.location.href = `thesistoggle_availability.php?id=${thesisId}&action=${actionType}`; // Correct path to your PHP script for thesis
    });
</script>
<script>
    // Load selected theses from localStorage
    function loadSelectedTheses() {
        let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
        document.querySelectorAll('.selectThesisCheckbox').forEach(checkbox => {
            if (selectedTheses.includes(checkbox.value)) {
                checkbox.checked = true;
            }
        });
    }

    // Save selected theses to localStorage
    function saveSelectedTheses() {
        let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
        document.querySelectorAll('.selectThesisCheckbox').forEach(checkbox => {
            if (checkbox.checked && !selectedTheses.includes(checkbox.value)) {
                selectedTheses.push(checkbox.value);
            } else if (!checkbox.checked && selectedTheses.includes(checkbox.value)) {
                selectedTheses = selectedTheses.filter(id => id !== checkbox.value);
            }
        });
        localStorage.setItem('selectedTheses', JSON.stringify(selectedTheses));
    }

    // Add event listener to checkboxes to save selections when changed
    document.querySelectorAll('.selectThesisCheckbox').forEach(checkbox => {
        checkbox.addEventListener('change', saveSelectedTheses);
    });

    // Function to print all selected theses
    function printAllThesisData() {
        let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
        if (selectedTheses.length === 0) {
            alert('Please select at least one thesis to print.');
            return;
        }

        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Theses</title></head><body>');
        printWindow.document.write('<h1 class="text-center">Selected Theses</h1>');

        var thesesParam = selectedTheses.join(','); // Concatenate the selected thesis IDs
        var thesesUrl = "get_selected_theses.php?ids=" + thesesParam;

        // Use fetch API to get the selected theses data
        fetch(thesesUrl)
            .then(response => response.text())
            .then(data => {
                printWindow.document.write(data); // Include the fetched HTML and QR codes
                printWindow.document.write('</body></html>');
                printWindow.document.close(); // Close the document for printing
                printWindow.print(); // Trigger the print dialog
            })
            .catch(error => console.error('Error fetching theses data:', error));
    }

    // On page load, restore selected theses
    document.addEventListener('DOMContentLoaded', loadSelectedTheses);
</script>

<!-- QR Code and Print Logic -->
<script>
    function printAllThesisData() {
        let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
        if (selectedTheses.length === 0) {
            alert('Please select at least one thesis to print.');
            return;
        }

        var thesesParam = selectedTheses.join(',');
        var printUrl = "get_selected_theses.php?ids=" + thesesParam;
        
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Theses</title></head><body>');
        
        fetch(printUrl)
            .then(response => response.text())
            .then(data => {
                printWindow.document.write(data);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            })
            .catch(error => console.error('Error:', error));
    }
</script>
<script>
    function downloadSelectedThesesPDF() {
    let selectedTheses = JSON.parse(localStorage.getItem('selectedTheses')) || [];
    if (selectedTheses.length === 0) {
        alert('Please select at least one thesis to download.');
        return;
    }

    var thesesParam = selectedTheses.join(',');
    var downloadUrl = "download_selected_theses.php?ids=" + thesesParam;
    
    window.location.href = downloadUrl;
}

</script>

<?php
// Backend PHP: get_selected_theses.php
if (isset($_GET['ids'])) {
    $thesis_ids = explode(',', $_GET['ids']);
    
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code FROM thesis WHERE id IN (" . implode(',', array_map('intval', $thesis_ids)) . ")";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<h1>Selected Theses</h1>';
        while ($row = $result->fetch_assoc()) {
            $qrCode = new QrCode("Title: {$row['title']}, Author: {$row['author']}, Advisor: {$row['advisor']}, Year: {$row['completion_year']}");
            $writer = new PngWriter();
            $qrCodeImage = $writer->write($qrCode);
            
            echo "<div>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
            echo "<p>Advisor: " . htmlspecialchars($row['advisor']) . "</p>";
            echo "<p>Year of Completion: " . htmlspecialchars($row['completion_year']) . "</p>";
            echo "<p>Library Code: " . htmlspecialchars($row['bookshelf_code']) . "</p>";
            echo "<img src='data:image/png;base64," . base64_encode($qrCodeImage->getString()) . "' />";
            echo "</div><hr>";
        }
    }
}
?>