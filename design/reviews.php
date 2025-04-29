<?php
// Include the database connection file and necessary libraries
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // For QR code library

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Number of books to display per page
$reviews_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$offset = ($current_page - 1) * $reviews_per_page;

// Prepare SQL based on whether a search query is present
if (!empty($search_query)) {
    $sql_count = "SELECT COUNT(*) as total_reviews FROM reviews r LEFT JOIN books b ON b.book_id = r.book_id WHERE b.title LIKE ?";
    $sql_reviews = "SELECT * FROM reviews r
                        LEFT JOIN books b ON b.book_id = r.book_id
                        LEFT JOIN students s ON s.student_id = r.student_id
                     WHERE b.title LIKE ? LIMIT ?, ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_reviews = $conn->prepare($sql_reviews);
    $like_query = '%' . $search_query . '%';
    $stmt_count->bind_param('s', $like_query);
    $stmt_reviews->bind_param('sii', $like_query, $offset, $reviews_per_page);
} else {
    $sql_count = "SELECT COUNT(*) as total_reviews FROM reviews";
    $sql_reviews = "SELECT * FROM reviews r
                        LEFT JOIN books b ON b.book_id = r.book_id
                        LEFT JOIN students s ON s.student_id = r.student_id
                    LIMIT ?, ?";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->bind_param('ii', $offset, $reviews_per_page);
}

$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_reviews_row = $result_count->fetch_assoc();
$total_reviews = $total_reviews_row['total_reviews'];
$total_pages = ceil($total_reviews / $reviews_per_page);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();

?>

<div class="row">
    <div class="col-sm-12 col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Reviews</div>
                <div class="search-container">
                    <!-- <button type="button" class="btn btn-success btn-icon me-2" onclick="window.location.href='admin_addbooks.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Add a New Book">
                        <i class="bi bi-plus-circle"></i> 
                    </button>
                    <button type="button" class="btn btn-primary btn-icon me-2" onclick="printAllBookData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                        <i class="bi bi-printer"></i>
                    </button> -->
                    <div class="input-group">
                        <form action="admin_reviews.php" method="GET">
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
                                <th style="width: 15%;">Student</th>
                                <th style="width: 20%;">Book Details</th>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 55%;">Review</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_reviews->num_rows > 0): ?>
                                <?php while ($row = $result_reviews->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-row gap-3 align-items-center justify-center">

                                                <img src="uploads/<?php echo $row['picture_path']; ?>" alt="Avatar Image" style="width: 80px; height: 80px; object-fit: cover;">

                                                <div class="div">
                                                    <p class="text-capitalize"><?php echo htmlspecialchars($row['name']) ?></p>
                                                    <p class="text-capitalize"><?php echo htmlspecialchars($row['lrn']) ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-row gap-3 align-items-center justify-center">

                                                <img src="<?php echo $row['book_image_path']; ?>" alt="Book Image" style="width: 120px; height: 100px; object-fit: cover;">

                                                <div class="div">
                                                    <p class="text-capitalize"><?php echo htmlspecialchars($row['title']) ?></p>
                                                    <p class="text-capitalize">ISBN: <?php echo htmlspecialchars($row['isbn']) ?></p>
                                                    <p>
                                                        <?php

                                                        $filledStars =  round($row['rating']);


                                                        for ($i = 1; $i <= 5; $i++) {
                                                            $starClass = $i <= $filledStars ? 'text-warning' : '';
                                                            echo '<span class="bi bi-star-fill ' . $starClass . '"></span>';
                                                        }

                                                        ?>

                                                    </p>
                                                </div>
                                            </div>

                                        </td>
                                        <td><?php echo htmlspecialchars(date('m/d/Y', strtotime($row['created']))); ?></td>
                                        <td><?php echo htmlspecialchars($row['review']); ?></td>
                                        <td>

                                            <!-- <td>
                                            <div class="actions">
                                                Request Book Button
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
                                        </td> -->
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%" class="text-center">No reviews found.</td>
                                </tr>
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
                            echo '<li class="page-item"><a class="page-link" href="admin_reviews.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $current_page) {
                                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="admin_reviews.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
                            }
                        }
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="admin_reviews.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
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
        button.addEventListener('click', function() {
            actionType = this.getAttribute('data-action');
            bookId = this.getAttribute('data-id');
        });
    });

    // On confirm button click, redirect to the appropriate action (activate/deactivate)
    document.getElementById('confirmAction').addEventListener('click', function() {
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