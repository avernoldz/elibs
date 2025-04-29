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
                $sql_count = "SELECT COUNT(*) as total_books FROM books WHERE is_archived = 0 AND title LIKE ?";
                $sql_books = "SELECT * FROM books WHERE is_archived = 0 AND title LIKE ? LIMIT ?, ?";
                $stmt_count = $conn->prepare($sql_count);
                $stmt_books = $conn->prepare($sql_books);
                $like_query = '%' . $search_query . '%';
                $stmt_count->bind_param('s', $like_query);
                $stmt_books->bind_param('sii', $like_query, $offset, $books_per_page);
            } else {
                $sql_count = "SELECT COUNT(*) as total_books FROM books WHERE is_archived = 0";
                $sql_books = "SELECT * FROM books WHERE is_archived = 0 LIMIT ?, ?";
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

                                                            <!-- QR Code Button --><a>
                                                            <button type="button" class="btn btn-link qrCodeBtn" data-id="<?php echo $row['book_id']; ?>">
                                                                <i class="bi bi-qr-code"></i>
                                                            </button></a>

                                                            
                                                            <!-- Archive Book Button -->
                                                                <a>
                                                                    <button type="button" class="btn btn-link archiveBook" data-id="<?php echo $row['book_id']; ?>">
                                                                        <i class="bi bi-archive text-warning"></i>
                                                                    </button>
                                                                </a>

                                                        
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

          <!-- Edit Book Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editBookLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookLabel">Edit Book</h5>
      </div>

      <div class="modal-body">
        <form id="editBookForm" method="POST" action="update_book.php">
          <input type="hidden" name="book_id" id="editBookId">

          <!-- Row start -->
          <div class="row">
            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editTitle" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="editTitle" placeholder="Enter Book Title" required>
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editAuthor" class="form-label">Author</label>
                <input type="text" class="form-control" name="author" id="editAuthor" placeholder="Enter Author Name" required>
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editIsbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" name="isbn" id="editIsbn" placeholder="Enter ISBN">
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editPublisher" class="form-label">Publisher</label>
                <input type="text" class="form-control" name="publisher" id="editPublisher" placeholder="Enter Publisher">
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editPublicationYear" class="form-label">Publication Year</label>
                <input type="text" class="form-control" name="publication_year" id="editPublicationYear" placeholder="Enter Year">
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editEdition" class="form-label">Edition</label>
                <input type="text" class="form-control" name="edition" id="editEdition" placeholder="Enter Edition">
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editQuantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" id="editQuantity" placeholder="Enter Quantity">
              </div>
            </div>

            <div class="col-xl-6 col-sm-12 col-12">
              <div class="mb-3">
                <label for="editBookshelfCode" class="form-label">Bookshelf Code</label>
                <input type="text" class="form-control" name="bookshelf_code" id="editBookshelfCode" placeholder="Enter Shelf Code">
              </div>
            </div>
          </div>
          <!-- Row end -->

          <!-- Form actions footer start -->
          <div class="form-actions-footer text-end">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Save Changes</button>
          </div>
          <!-- Form actions footer end -->
        </form>
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
    window.open('print_books.php', '_blank');
}
document.querySelectorAll('.qrCodeBtn').forEach(button => {
    button.addEventListener('click', function () {
        const bookId = this.getAttribute('data-id');
        const qrUrl = `generate_qr.php?book_id=${bookId}`;

        const img = new Image();
        img.src = qrUrl;
        img.style.maxHeight = '200px';

        img.onload = function () {
            Swal.fire({
                title: `QR Code for Book ID: ${bookId}`,
                html: `
                    <img src="${qrUrl}" style="max-height: 200px;" />
                    <br>
                    <a href="${qrUrl}" download="book_${bookId}_qr.png" class="btn btn-sm btn-primary mt-2">
                        Download QR Code
                    </a>
                `,
                showConfirmButton: false,
                showCloseButton: true,
            });
        };

        img.onerror = function () {
            Swal.fire('Error', 'Failed to load QR code.', 'error');
        };
    });
});

document.querySelectorAll('.editBook').forEach(button => {
    button.addEventListener('click', function () {
        const bookId = this.getAttribute('data-id');

        fetch(`get_book.php?book_id=${bookId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editBookId').value = data.book_id;
                document.getElementById('editTitle').value = data.title;
                document.getElementById('editAuthor').value = data.author;
                document.getElementById('editIsbn').value = data.isbn;
                document.getElementById('editPublisher').value = data.publisher;
                document.getElementById('editPublicationYear').value = data.publication_year;
                document.getElementById('editEdition').value = data.edition;
                document.getElementById('editQuantity').value = data.quantity;
                document.getElementById('editBookshelfCode').value = data.bookshelf_code;
            })
            .catch(error => {
                console.error('Error fetching book data:', error);
                Swal.fire('Error', 'Failed to load book details.', 'error');
            });
    });
});

document.querySelectorAll('.archiveBook').forEach(button => {
    button.addEventListener('click', function () {
        const bookId = this.getAttribute('data-id');

        Swal.fire({
            title: 'Archive Book',
            text: 'Are you sure you want to archive this book? It will be moved from the active catalog.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, archive it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `archive_book.php?book_id=${bookId}`;
            }
        });
    });
});



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

            <!-- Place this inside a <script> tag at the bottom -->
<script>
document.getElementById('editBookForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    const form = e.target;
    const formData = new FormData(form);

    fetch('update_book.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Expecting JSON response from PHP
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message
            }).then(() => {
                // Optionally close the modal and reload the page
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the book.'
        });
    });
});
</script>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'archived'): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Archived',
    text: 'Book has been successfully archived!',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php endif; ?>


<!-- Modal View QR Code -->
<div class="modal modal-dark fade" id="viewQRCode" tabindex="-1" aria-labelledby="viewQRCode" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="QR Code" id="qr-img" width="250px" height="auto">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Reuse existing qrCodeBtn handler to trigger modal display
const qrModal = new bootstrap.Modal(document.getElementById('viewQRCode'));

document.querySelectorAll('.qrCodeBtn').forEach(button => {
    button.addEventListener('click', function () {
        const bookId = this.getAttribute('data-id');
        const qrUrl = `generate_qr.php?book_id=${bookId}`;

        const qrImg = document.getElementById('qr-img');
        qrImg.src = qrUrl;

        qrModal.show();
    });
});
</script>





            <?php
            $conn->close();
            ?>
