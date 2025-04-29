<?php
// Include database connection
include 'connection.php';

// Check if a session is not already started, then start a new session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize search query and filter
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'title';

// Define allowed filters to prevent SQL injection
$allowed_filters = ['title', 'author', 'advisor', 'strand'];
if (!in_array($filter, $allowed_filters)) {
    $filter = 'title'; // Default to title if the filter is invalid
}

// Number of theses to display per page
$theses_per_page = 10;

// Get the current page from the URL, if not set, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record for the current page
$offset = ($current_page - 1) * $theses_per_page;

// Function to generate pagination links
function renderPagination($total_pages, $current_page, $search_query = '', $filter = 'title') {
    $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';
    $filter_param = '&filter=' . urlencode($filter);

    echo '<div class="pagination justify-content-center mt-4">';
    
    // Previous button
    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="student_thesiscatalog.php?page=' . ($current_page - 1) . $search_param . $filter_param . '">Previous</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
    }

    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="student_thesiscatalog.php?page=' . $i . $search_param . $filter_param . '">' . $i . '</a></li>';
        }
    }

    // Next button
    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="student_thesiscatalog.php?page=' . ($current_page + 1) . $search_param . $filter_param . '">Next</a></li>';
    } else {
        echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
    }

    echo '</div>';
}

// Prepare the SQL query based on whether a search term is provided
if (!empty($search_query)) {
    // Count total theses for pagination
    $query = "SELECT COUNT(*) FROM thesis WHERE $filter LIKE ?";
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

// Fetch theses for the current page, including availability
if (!empty($search_query)) {
    $query = "SELECT id, title, author, advisor, strand, completion_year, bookshelf_code, availability 
            FROM thesis 
            WHERE $filter LIKE ? 
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
        <div class="card-header d-flex align-items-center justify-content-between">
    <div class="card-title">Theses</div>
    <div class="search-container d-flex align-items-center">
        
        <!-- Dropdown for filtering -->
        <form action="student_thesiscatalog.php" method="GET" class="d-flex align-items-center">
            <select class="select-single js-states form-control me-2" name="filter" title="Select Filter" data-live-search="true">
                <option value="title" <?php if ($filter === 'title') echo 'selected'; ?>>Title</option>
                <option value="author" <?php if ($filter === 'author') echo 'selected'; ?>>Author(s)</option>
                <option value="advisor" <?php if ($filter === 'advisor') echo 'selected'; ?>>Advisor</option>
                <option value="strand" <?php if ($filter === 'strand') echo 'selected'; ?>>Strand</option>
            </select>

            <!-- Dropdown for year filtration -->
            <select class="select-single js-states form-control me-2" name="year" title="Select Year" data-live-search="true">
                <option value="">Select Year</option>
                <?php
                // Example years, you can customize this
                for ($year = 2020; $year <= date("Y"); $year++) {
                    echo "<option value='$year' " . (($selected_year === $year) ? 'selected' : '') . ">$year</option>";
                }
                ?>
            </select>

            <!-- Search input -->
            <input type="text" class="form-control" name="search" placeholder="Search Thesis" value="<?php echo htmlspecialchars($search_query); ?>">
            <button class="btn btn-primary ms-2" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>
            
        
    


            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0 thesis-table"> <!-- Ensure this class matches in the JS function -->
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Advisor</th>
                                <th>Strand</th>
                                <th>Year</th>
                                <th>Library Code</th>
                                <th>Availability</th>
                                <th>Abstract</th> <!-- New column for abstract -->
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
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td>
                                            <div class="media-box-body">
                                                <div class="text-truncate"><?php echo htmlspecialchars($row['author']); ?></div>
                                                
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
                                            <?php if ($row['availability']) { ?>
                                                <div class="actions">
                                                    <a href="view_thesis.php" class="viewRow" data-id="<?php echo $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewRow">
                                                        <i class="bi bi-eye text-green"></i>
                                                    </a>
                                                </div>
                                            <?php } else { ?>
                                                <div class="actions">
                                                    <a href="">
                                                    <i class="bi bi-eye-slash text-red"></i> <!-- Display eye-slash icon if unavailable -->
                                                    </a>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                // If no theses are found
                                echo "<tr><td colspan='8'>No theses found.</td></tr>"; // Updated colspan
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
// Close the database connection
$conn->close();
?>
