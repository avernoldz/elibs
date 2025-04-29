<?php
// Include the database connection file
include 'connection.php';

// Fetch book return data
$sql = "SELECT br.request_id, br.request_date, br.status, br.expected_pickup_date, br.due_date, br.return_date, br.is_returned, 
               b.title AS book_title, b.author, b.isbn, b.publisher, b.publication_year, b.edition, b.quantity, b.bookshelf_code,
               s.name AS student_name
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id";

$result = $conn->query($sql);
?>


<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Book Returned History</div>
            <div class="search-container">
                <button type="button" class="btn btn-success btn-icon me-2" onclick="window.location.href='admin_bookreturned.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Return Book">
                    <i class="bi bi-plus-circle"></i> Return Book
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Book Title</th>
                            <th>Request Date</th>
                            <th>Returned Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                                    <td class="text-capitalize"><?= htmlspecialchars($row['book_title']) ?></td>
                                    <td><?= htmlspecialchars(date('m/d/Y', strtotime($row['request_date']))) ?></td>
                                    <td><?= !empty($row['return_date']) ? htmlspecialchars(date('m/d/Y', strtotime($row['return_date']))) : '' ?></td>
                                    <td>
                                        <?php if ($row['is_returned']): ?>
                                            <span class="text-success"><i class="bi bi-check-circle"></i> Returned</span>
                                        <?php else: ?>
                                            <span class="text-danger"><i class="bi bi-clock-history"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-info viewRow"
                                            data-title="<?= htmlspecialchars($row['book_title']) ?>"
                                            data-author="<?= htmlspecialchars($row['author']) ?>"
                                            data-isbn="<?= htmlspecialchars($row['isbn']) ?>"
                                            data-publisher="<?= htmlspecialchars($row['publisher']) ?>"
                                            data-year="<?= htmlspecialchars($row['publication_year']) ?>"
                                            data-edition="<?= htmlspecialchars($row['edition']) ?>"
                                            data-quantity="<?= htmlspecialchars($row['quantity']) ?>"
                                            data-bookshelf="<?= htmlspecialchars($row['bookshelf_code']) ?>">
                                            📖 View Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No book requests found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        // View Book Details using SweetAlert
        $('.viewRow').on('click', function () {
            Swal.fire({
                title: '📖 Book Details',
                html: `
                    <p><strong>Title:</strong> ${$(this).data('title')}</p>
                    <p><strong>Author:</strong> ${$(this).data('author')}</p>
                    <p><strong>ISBN:</strong> ${$(this).data('isbn')}</p>
                    <p><strong>Publisher:</strong> ${$(this).data('publisher')}</p>
                    <p><strong>Year:</strong> ${$(this).data('year')}</p>
                    <p><strong>Edition:</strong> ${$(this).data('edition')}</p>
                    <p><strong>Quantity:</strong> ${$(this).data('quantity')}</p>
                    <p><strong>Bookshelf:</strong> ${$(this).data('bookshelf')}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#3085d6'
            });
        });
    });
</script>