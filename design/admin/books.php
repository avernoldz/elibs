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

                            <!-- Button for Excel Export -->
                            <button type="button" class="btn btn-info btn-icon me-2" onclick="exportToExcel()" data-bs-toggle="tooltip" data-bs-placement="top" title="Export to Excel">
                                <i class="bi bi-file-earmark-excel"></i>
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
                                        <!--  <th>Avail.</th> -->
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result_books->num_rows > 0): ?>
                                            <?php while ($row = $result_books->fetch_assoc()): ?>
                                                <tr>
                                                    
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
                                                <!--    <td><span class="badge <?php echo $row['availability'] ? 'shade-green' : 'shade-red'; ?> min-70"><?php echo $row['availability'] ? 'Available' : 'Unavailable'; ?></span></td> -->
                                                <td>
                                                        <div class="actions"> <a>
                                                            <!-- Edit Book Button -->
                                                            <button  type="button" class="btn btn-link editBook" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $row['book_id']; ?>">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button> </a>
                                                            
                                                            <!-- Request Book Button --><a>
                                                            <button type="button" class="btn btn-link requestBook" data-bs-toggle="modal" data-bs-target="#requestModal" data-id="<?php echo $row['book_id']; ?>">
                                                                <i class="bi bi-journal-plus"></i> 
                                                            </button></a>
                                                            
                                                            <a>
                                                            <?php if ($row['availability'] == 1) { ?>
                                                                <button type="button" class="btn btn-link deactivateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="deactivate" data-id="<?php echo $row['book_id']; ?>">
                                                                    <i class="bi bi-x-circle text-red"></i>
                                                                </button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn-link activateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="activate" data-id="<?php echo $row['book_id']; ?>">
                                                                    <i class="bi bi-check-circle text-green"></i>
                                                                </button> </a>
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




            <script>
                // Function to show SweetAlert instead of default alerts
                function showWarning(title, text) {
                    Swal.fire({
                        icon: 'warning',
                        title: title,
                        text: text,
                    });
                }

                function showSuccess(title, text) {
                    Swal.fire({
                        icon: 'success',
                        title: title,
                        text: text,
                    });
                }

                function showError(title, text) {
                    Swal.fire({
                        icon: 'error',
                        title: title,
                        text: text,
                    });
                }

                function printAllBookData() {
    Swal.fire({
        title: 'Print All Books?',
        text: 'This will download all book data as a Word file.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes, Download Word',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement("form");
            form.method = "GET"; // GET to trigger export param
            form.action = "get_selected_books.php"; // same endpoint, handles ?export=doc
            form.target = "_blank";

            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "export";
            input.value = "doc";
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    });
}


                // Confirmation modal for activating/deactivating books
                function confirmAction(bookId, action) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `Do you want to ${action} this book?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `toggle_availability.php?book_id=${bookId}&action=${action}`;
                        }
                    });
                }

                // Attach click event to buttons
                document.querySelectorAll('.deactivateRow, .activateRow').forEach(button => {
                    button.addEventListener('click', function () {
                        let actionType = this.getAttribute('data-action');
                        let bookId = this.getAttribute('data-id');
                        confirmAction(bookId, actionType);
                    });
                });
            </script>

            <script>
                // Function to show SweetAlert for book request
                function requestBook(bookId) {
                    Swal.fire({
                        title: 'Request Book',
                        html: `
                            <input type="hidden" id="modalBookId" value="${bookId}">
                            <label for="studentSearch" class="form-label">Search Student Account</label>
                            <input type="text" class="form-control" id="studentSearch" placeholder="Enter student name or LRN">
                            <div id="studentResult"></div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit Request',
                        preConfirm: () => {
                            const bookId = document.getElementById('modalBookId').value;
                            const studentName = document.getElementById('studentSearch').value;
                            if (!studentName) {
                                Swal.showValidationMessage('Please select a student account');
                            }
                            return { bookId, studentName };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('request_book.php', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `book_id=${result.value.bookId}&student=${encodeURIComponent(result.value.studentName)}`
                            })
                            .then(response => response.text())
                            .then(data => {
                                Swal.fire('Success', 'Book request submitted successfully!', 'success');
                            })
                            .catch(error => {
                                Swal.fire('Error', 'Failed to submit request.', 'error');
                            });
                        }
                    });
                }

                // Attach event listeners to book request buttons
                document.querySelectorAll('.requestBook').forEach(button => {
                    button.addEventListener('click', function () {
                        let bookId = this.getAttribute('data-id');
                        requestBook(bookId);
                    });
                });
            </script>

            <script>
            function requestBook(bookId) {
                Swal.fire({
                    title: 'Request Book',
                    html: `
                        <input type="hidden" id="modalBookId" value="${bookId}">
                        <label for="studentSearch" class="form-label">Search Student</label>
                        <input type="text" class="form-control" id="studentSearch" placeholder="Enter Student Name or LRN" onkeyup="searchStudent()">
                        <div id="studentResult" class="mt-2"></div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Submit Request',
                    preConfirm: () => {
                        const selectedStudentId = document.querySelector('input[name="selectedStudent"]:checked');
                        if (!selectedStudentId) {
                            Swal.showValidationMessage('Please select a student');
                            return false;
                        }
                        return {
                            bookId: document.getElementById('modalBookId').value,
                            studentId: selectedStudentId.value
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('request_book.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `book_id=${result.value.bookId}&student_id=${encodeURIComponent(result.value.studentId)}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            Swal.fire('Success', 'Book request submitted successfully!', 'success');
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Failed to submit request.', 'error');
                        });
                    }
                });
            }

            // Function to search students by LRN or name
            function searchStudent() {
                let query = document.getElementById('studentSearch').value.trim();
                if (query.length < 2) return; // Avoid unnecessary requests

                fetch(`search_student.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let resultHtml = '';
                    if (data.length > 0) {
                        data.forEach(student => {
                            resultHtml += `
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="selectedStudent" value="${student.id}">
                                    <label class="form-check-label">${student.name} (LRN: ${student.lrn})</label>
                                </div>
                            `;
                        });
                    } else {
                        resultHtml = '<p class="text-danger">No students found.</p>';
                    }
                    document.getElementById('studentResult').innerHTML = resultHtml;
                })
                .catch(error => {
                    console.error('Error fetching students:', error);
                });
            }
            </script>





            <?php
            $conn->close();
            ?>
