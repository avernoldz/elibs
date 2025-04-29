<?php
// Include the database connection file and necessary libraries
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php'; // For QR code library

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Number of books to display per page
$books_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = isset($_GET['filter_option']) ? $_GET['filter_option'] : '';
$offset = ($current_page - 1) * $books_per_page;

// Prepare SQL based on whether a search query (or any filter) is present
if (!empty($search_query)) {

    $conditions = [];

    // Student-level filters
    if (!empty($_GET['lrn'])) {
        $lrn = $conn->real_escape_string($_GET['lrn']);
        $conditions[] = "s.lrn LIKE '%$lrn%'";
    }
    if (!empty($_GET['name'])) {
        $name = $conn->real_escape_string($_GET['name']);
        $conditions[] = "s.name LIKE '%$name%'";
    }
    if (!empty($_GET['grade_level'])) {
        $grade_level = intval($_GET['grade_level']);
        $conditions[] = "s.grade_level = $grade_level";
    }
    if (!empty($_GET['month'])) {
        $month = str_pad(intval($_GET['month']), 2, '0', STR_PAD_LEFT);
        $conditions[] = "MONTH(br.request_date) = '$month'";
    }
    if (!empty($_GET['year'])) {
        $year = intval($_GET['year']);
        $conditions[] = "YEAR(br.request_date) = '$year'";
    }

    // Combine all conditions
    $filter_conditions = '';
    if (!empty($conditions)) {
        $filter_conditions = ' AND ' . implode(' AND ', $conditions);
    }


    // Count total students who have matching requests
    $sql_count = "SELECT COUNT(DISTINCT s.student_id) as total_books FROM 
                  students s
                  LEFT JOIN book_requests br ON br.student_id = s.student_id 
                  WHERE br.student_id IS NOT NULL $filter_conditions";

    // Fetch filtered student borrowing report
    $sql_books = "SELECT 
                        s.lrn,
                        s.student_id,
                        s.name,
                        s.grade_level,
                        COUNT(br.book_id) AS total_borrowed,
                        SUM(CASE WHEN br.is_returned = 1 THEN 1 ELSE 0 END) AS total_returned,
                        SUM(CASE WHEN br.is_overdue = 1 THEN 1 ELSE 0 END) AS total_overdue
                    FROM 
                        students s
                    LEFT JOIN 
                        book_requests br ON br.student_id = s.student_id
                    WHERE 
                        br.student_id IS NOT NULL
                        $filter_conditions
                    GROUP BY 
                        s.student_id
                    LIMIT ?, ?";

    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);

    $stmt_books->bind_param('ii', $offset, $books_per_page);
} else {
    // No filters: return all students with borrowing data
    $sql_count = "SELECT COUNT(DISTINCT s.student_id) as total_books FROM 
                    students s
                    LEFT JOIN book_requests br ON br.student_id = s.student_id
                    WHERE br.student_id IS NOT NULL";

    $sql_books = "SELECT 
                        s.student_id,
                        s.lrn,
                        s.name,
                        s.grade_level,
                        COUNT(br.book_id) AS total_borrowed,
                        SUM(CASE WHEN br.is_returned = 1 THEN 1 ELSE 0 END) AS total_returned,
                        SUM(CASE WHEN br.is_overdue = 1 THEN 1 ELSE 0 END) AS total_overdue
                    FROM 
                        students s
                    LEFT JOIN 
                        book_requests br ON br.student_id = s.student_id
                    WHERE 
                        br.student_id IS NOT NULL
                    GROUP BY 
                        s.student_id
                    LIMIT ?, ?";

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
                <div class="card-title">Student Reports</div>
                <div class="search-container">
                    <button type="button" class="btn btn-primary btn-icon me-2" onclick="printAllBookData()" data-bs-toggle="tooltip" data-bs-placement="top" title="Print">
                        <i class="bi bi-printer"></i>
                    </button>
                    <!-- <form id="search-date" action="admin_bookreport.php" method="GET">

                        <div class="input-group">
                            <input type="date" class="form-control" name="search" placeholder="Search Book Title" value="<?php echo htmlspecialchars($search_query); ?>">

                        </div>
                        <button class="btn" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form> -->

                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#advance-filter-modal">Advance Filter</button>

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Grade Level</th>
                                <th>Borrowed Books</th>
                                <th>Returned Books</th>
                                <th>Overdued Books</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_books->num_rows > 0): ?>
                                <?php while ($row = $result_books->fetch_assoc()):
                                    // print_r($row);
                                ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input type="checkbox" name="selectedBooks[]" class="selectBooksCheckbox form-check-input" value="<?php echo $row['student_id']; ?>">
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                                        <td><?php echo htmlspecialchars($row['total_borrowed']); ?></td>
                                        <td><?php echo htmlspecialchars($row['total_returned']); ?></td>
                                        <td><?php echo htmlspecialchars($row['total_overdue']); ?></td>

                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">No books found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
                <!-- Pagination Links -->
                <div class="pagination justify-content-center mt-4">
                    <ul class="pagination">
                        <?php
                        $filter_params = '';
                        foreach ($_GET as $key => $value) {
                            if ($key !== 'page' && !empty($value)) {
                                $filter_params .= '&' . urlencode($key) . '=' . urlencode($value);
                            }
                        }
                        if ($current_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="admin_studentreport.php?page=' . ($current_page - 1) . $filter_params . '">Previous</a></li>';
                        } else {
                            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                        }
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $current_page) {
                                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="admin_studentreport.php?page=' . $i . $filter_params . '">' . $i . '</a></li>';
                            }
                        }
                        if ($current_page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="admin_studentreport.php?page=' . ($current_page + 1) . $filter_params . '">Next</a></li>';
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

<div class="modal fade" id="advance-filter-modal" tabindex="-1" aria-labelledby="advanceFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="advanceFilterModalLabel">Advanced Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="advanced-filter-form" action="admin_studentreport.php" method="GET">
                    <div class="mb-3">
                        <label for="filter-option" class="form-label">Filter by</label>
                        <select class="form-select" id="filter-option" name="filter_option">
                            <option value="" disabled selected>Select a filter</option>
                            <option value="lrn">LRN</option>
                            <option value="name">Name</option>
                            <option value="grade_level">Grade Level</option>
                            <option value="month">Month</option>
                            <option value="year">Year</option>
                        </select>

                        <!-- LRN -->
                        <div class="mt-3 d-none" id="lrn-container">
                            <label for="input-lrn" class="form-label">Enter LRN</label>
                            <input type="text" class="form-control" id="input-lrn" name="lrn" placeholder="123456789">
                        </div>

                        <!-- Name -->
                        <div class="mt-3 d-none" id="name-container">
                            <label for="input-name" class="form-label">Enter Name</label>
                            <input type="text" class="form-control" id="input-name" name="name" placeholder="Juan Dela Cruz">
                        </div>

                        <!-- Grade Level -->
                        <div class="mt-3 d-none" id="grade-level-container">
                            <label for="input-grade" class="form-label">Grade Level</label>
                            <select class="form-select" id="input-grade" name="grade_level">
                                <option value="" disabled selected>Select grade level</option>
                                <?php for ($i = 6; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>">Grade <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <!-- Month -->
                        <div class="mt-3 d-none" id="month-container">
                            <label for="input-month" class="form-label">Select Month</label>
                            <select class="form-select" id="input-month" name="month">
                                <option value="" disabled selected>Select month</option>
                                <?php
                                $months = [
                                    '01' => 'January',
                                    '02' => 'February',
                                    '03' => 'March',
                                    '04' => 'April',
                                    '05' => 'May',
                                    '06' => 'June',
                                    '07' => 'July',
                                    '08' => 'August',
                                    '09' => 'September',
                                    '10' => 'October',
                                    '11' => 'November',
                                    '12' => 'December'
                                ];
                                foreach ($months as $num => $name) {
                                    echo "<option value=\"$num\">$name</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Year -->
                        <div class="mt-3 d-none" id="year-container">
                            <label for="input-year" class="form-label">Enter Year</label>
                            <input type="number" class="form-control" id="input-year" name="year" placeholder="e.g. 2025">
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="clear-filters" class="btn btn-danger">Clear Filters</button>
                <button type="submit" form="advanced-filter-form" class="btn btn-primary">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

<script>
    const filterSelect = document.getElementById('filter-option');
    const containers = {
        lrn: document.getElementById('lrn-container'),
        name: document.getElementById('name-container'),
        grade_level: document.getElementById('grade-level-container'),
        month: document.getElementById('month-container'),
        year: document.getElementById('year-container')
    };

    filterSelect.addEventListener('change', function() {
        // Hide all first
        for (let key in containers) {
            containers[key].classList.add('d-none');
        }

        const selected = this.value;
        if (containers[selected]) {
            containers[selected].classList.remove('d-none');
        }
    });

    document.getElementById('clear-filters').addEventListener('click', function() {
        // Clear filter by reloading without parameters
        const form = document.getElementById('advanced-filter-form');
        const actionUrl = form.getAttribute('action');
        window.location.href = actionUrl;
    });
</script>


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
        var printUrl = "get_selected_student_report.php?ids=" + booksParam;

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
        const printUrl = "get_selected_student_report.php?ids=" + booksParam;

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