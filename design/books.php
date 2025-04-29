<?php
// Include the database connection file and necessary libraries
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // For QR code library

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Number of books to display per page
$books_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($current_page - 1) * $books_per_page;

// Prepare SQL based on whether a search query is present
if (!empty($search_query)) {
    $sql_count = "SELECT COUNT(*) as total_books FROM books WHERE title LIKE ?";
    $sql_books = "SELECT * FROM books WHERE title LIKE ? LIMIT ?, ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);
    $like_query = '%' . $search_query . '%';
    $stmt_count->bind_param('s', $like_query);
    $stmt_books->bind_param('sii', $like_query, $offset, $books_per_page);
} else {
    $sql_count = "SELECT COUNT(*) as total_books FROM books";
    $sql_books = "SELECT * FROM books LIMIT ?, ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);
    $stmt_books->bind_param('ii', $offset, $books_per_page);
}

$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_books_row = $result_count->fetch_assoc();
$total_books = $total_books_row['total_books'];
$total_pages = ceil($total_books / $books_per_page);
$stmt_books->execute();
$result_books = $stmt_books->get_result();

?>

<div class="row">
    <div class="col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Books Catalog</div>
                <div class="search-container">
                    <button type="button" class="btn btn-success btn-icon me-2" onclick="window.location.href='admin_addbooks.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Add a New Book">
                        <i class="bi bi-plus-circle"></i> 
                    </button>
                    <button type="button" class="btn btn-primary btn-icon me-2" onclick="printAllBookData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                        <i class="bi bi-printer"></i>
                    </button>
                    <div class="input-group">
                        <form action="admin_bookcatalog.php" method="GET">
                            <input type="text" class="form-control" name="search" placeholder="Search Book Title" value="<?php echo htmlspecialchars($search_query); ?>">
                            <button class="btn" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Cover</th>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>Publ.</th>
                                <th>Pub. Yr.</th>
                                <th>Edition</th>
                                <th>Qty.</th>
                                <th>Shelf</th>
                                <th>Avail.</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_books->num_rows > 0): ?>
                                <?php while ($row = $result_books->fetch_assoc()): ?>
                                    <tr>
                                        <td><input type="checkbox" name="selectedBooks[]" class="selectBooksCheckbox" value="<?php echo $row['book_id']; ?>"></td>
                                        <td><img src="<?php echo $row['book_image_path']; ?>" alt="Book Image" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                        <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['author']); ?></td>
                                        <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['publisher']); ?></td>
                                        <td><?php echo htmlspecialchars($row['publication_year']); ?></td>
                                        <td><?php echo htmlspecialchars($row['edition']); ?></td>
                                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($row['bookshelf_code']); ?></td>
                                        <td><span class="badge <?php echo $row['availability'] ? 'shade-green' : 'shade-red'; ?> min-70"><?php echo $row['availability'] ? 'Available' : 'Unavailable'; ?></span></td>
                                        <td>
                                            <div class="actions">
                                                <!-- Request Book Button -->
                                                <a href="
                                                <button type="button" class="btn btn-link requestBook" data-bs-toggle="modal" data-bs-target="#requestModal" data-id="<?php echo $row['book_id']; ?>">
                                                    <i class="bi bi-journal-plus"></i> 
                                                </button>
                                                </a>
                                                <?php if ($row['availability'] == 1) { ?>
                                                    <a href="
                                                    <button type="button" class="btn btn-link deactivateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="deactivate" data-id="<?php echo $row['book_id']; ?>">
                                                        <i class="bi bi-x-circle text-red"></i>
                                                    </button>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="
                                                    <button type="button" class="btn btn-link activateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="activate" data-id="<?php echo $row['book_id']; ?>">
                                                        <i class="bi bi-check-circle text-green"></i>
                                                    </button>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="9">No books found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                </div>
                 <!-- Pagination Links -->
                <div class="pagination justify-content-center mt-4">
                    <ul class="pagination">
                        <?php
                        $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                        if ($current_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="admin_bookcatalog.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $current_page) {
                                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="admin_bookcatalog.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
                            }
                        }
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="admin_bookcatalog.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Requesting Book -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="request_book.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Request Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="book_id" id="modalBookId">
                    
                    <!-- New Search Field for Student Account -->
                    <div class="mb-3">
                        <label for="studentSearch" class="form-label">Search Student Account</label>
                        <input type="text" class="form-control" id="studentSearch" placeholder="Enter student name or LRN">
                        <div id="studentResult"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('studentSearch').addEventListener('input', function() {
        const query = this.value;

        if (query.length >= 3) { // Search after 3 characters
            fetch(`search_student.php?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    const resultDiv = document.getElementById('studentResult');
                    resultDiv.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(student => {
                            const studentItem = document.createElement('div');
                            studentItem.textContent = `${student.name} - ${student.lrn}`;
                            studentItem.className = 'student-item';
                            studentItem.addEventListener('click', () => {
                                document.getElementById('requesterName').value = student.name;
                                document.getElementById('studentSearch').value = student.name;
                                resultDiv.innerHTML = '';
                            });
                            resultDiv.appendChild(studentItem);
                        });
                    } else {
                        resultDiv.innerHTML = '<div>No students found.</div>';
                    }
                })
                .catch(error => console.error('Error fetching student data:', error));
        }
    });
</script>




<!-- Modal for confirming action -->
<div class="modal fade" id="modalDark" tabindex="-1" aria-labelledby="modalDarkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDarkLabel">Change Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the availability status of this book?
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
    let bookId = '';
    
    // Attach click listeners to deactivateRow and activateRow buttons
    document.querySelectorAll('.deactivateRow, .activateRow').forEach(button => {
        button.addEventListener('click', function () {
            actionType = this.getAttribute('data-action');
            bookId = this.getAttribute('data-id');
        });
    });

    // On confirm button click, redirect to the appropriate action (activate/deactivate)
    document.getElementById('confirmAction').addEventListener('click', function () {
        window.location.href = `toggle_availability.php?book_id=${bookId}&action=${actionType}`;
    });
</script>

<script>
    // Function to print all selected books with QR codes
    function printAllBookData() {
        let selectedBooks = Array.from(document.querySelectorAll('.selectBooksCheckbox:checked')).map(cb => cb.value);
        if (selectedBooks.length === 0) {
            alert('Please select at least one book to print.');
            return;
        }

        var booksParam = selectedBooks.join(',');
        var printUrl = "get_selected_books.php?ids=" + booksParam;

        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Books</title></head><body>');

        // Fetch selected books data along with QR codes for printing
        fetch(printUrl)
            .then(response => response.text())
            .then(data => {
                printWindow.document.write(data);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            })
            .catch(error => console.error('Error fetching books data:', error));
    }
</script>

<script>
    function printSelectedBooks() {
        let selectedBooks = Array.from(document.querySelectorAll('.selectBooksCheckbox:checked')).map(cb => cb.value);
        if (selectedBooks.length === 0) {
            alert('Please select at least one book to print.');
            return;
        }

        let form = document.createElement("form");
        form.method = "POST";
        form.action = "print_books.php";
        
        selectedBooks.forEach(bookId => {
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "selectedBooks[]";
            input.value = bookId;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    // Function to save the selected book IDs in localStorage
    function updateLocalStorage(bookId, isChecked) {
        let selectedBooks = JSON.parse(localStorage.getItem('selectedBooks')) || [];

        if (isChecked) {
            if (!selectedBooks.includes(bookId)) selectedBooks.push(bookId);
        } else {
            selectedBooks = selectedBooks.filter(id => id !== bookId);
        }
        localStorage.setItem('selectedBooks', JSON.stringify(selectedBooks));
    }

    // Function to load selected books from localStorage on page load
    function loadSelections() {
        const selectedBooks = JSON.parse(localStorage.getItem('selectedBooks')) || [];
        selectedBooks.forEach(bookId => {
            const checkbox = document.querySelector(`.selectBooksCheckbox[value="${bookId}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }

    // Event listener for book selection checkboxes
    document.querySelectorAll('.selectBooksCheckbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateLocalStorage(this.value, this.checked);
        });
    });

    // Call loadSelections() on page load to restore selections
    document.addEventListener('DOMContentLoaded', loadSelections);

    // Function to print all selected books with QR codes
    function printAllBookData() {
        const selectedBooks = JSON.parse(localStorage.getItem('selectedBooks')) || [];
        
        if (selectedBooks.length === 0) {
            alert('Please select at least one book to print.');
            return;
        }

        const booksParam = selectedBooks.join(',');
        const printUrl = "get_selected_books.php?ids=" + booksParam;

        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Books</title></head><body>');

        fetch(printUrl)
            .then(response => response.text())
            .then(data => {
                printWindow.document.write(data);
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            })
            .catch(error => console.error('Error fetching books data:', error));
    }

    // Clear selections from localStorage when necessary, such as when navigating away
    function clearSelections() {
        localStorage.removeItem('selectedBooks');
    }
</script>

<?php
$conn->close();
?>
