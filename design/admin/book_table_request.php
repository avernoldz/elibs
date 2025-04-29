<?php
// Include database connection
include 'connection.php';

// Fetch book request history
$sql = "SELECT br.request_id, br.request_date, br.status, 
               COALESCE(br.expected_pickup_date, '') AS expected_pickup_date, 
               COALESCE(br.due_date, '') AS due_date, 
               b.title AS book_title, b.author, b.isbn, b.publisher, 
               b.publication_year, b.edition, b.quantity, b.bookshelf_code,
               s.name AS student_name
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Book Request History</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table v-middle">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Book Title</th>
                        <th>Request ID</th>
                        <th>Request Date</th>
                        <th>Pickup Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['book_title']) ?></td>
                                <td class="request-id"><?= htmlspecialchars($row['request_id']) ?></td>
                                <td><?= htmlspecialchars($row['request_date']) ?></td>
                                <td><?= htmlspecialchars($row['expected_pickup_date'] ?: 'Not Set') ?></td>
                                <td><?= htmlspecialchars($row['due_date'] ?: 'Not Set') ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Approved') : ?>
                                        <span class="text-green"><i class="bi bi-check-circle"></i> Approved</span>
                                    <?php elseif ($row['status'] == 'Pending') : ?>
                                        <span class="text-blue"><i class="bi bi-clock-history"></i> Pending</span>
                                    <?php else : ?>
                                        <span class="text-red"><i class="bi bi-x-circle"></i> Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <?php if ($row['status'] == 'Approved') : ?>
                                            <a href="#" class="viewRow" data-title="<?= htmlspecialchars($row['book_title']) ?>" data-author="<?= htmlspecialchars($row['author']) ?>" data-isbn="<?= htmlspecialchars($row['isbn']) ?>" data-publisher="<?= htmlspecialchars($row['publisher']) ?>" data-year="<?= htmlspecialchars($row['publication_year']) ?>" data-edition="<?= htmlspecialchars($row['edition']) ?>" data-quantity="<?= htmlspecialchars($row['quantity']) ?>" data-bookshelf="<?= htmlspecialchars($row['bookshelf_code']) ?>">
                                                <i class="bi bi-list text-green"></i>
                                            </a>
                                        <?php elseif ($row['status'] == 'Pending') : ?>
                                            <a href="#" class="approveRow" data-id="<?= $row['request_id'] ?>">
                                                <i class="bi bi-check-circle text-green"></i>
                                            </a>
                                            <a href="#" class="rejectRow" data-id="<?= $row['request_id'] ?>">
                                                <i class="bi bi-x-circle text-red"></i>
                                            </a>
                                            <a href="#" class="setPickupDate" data-id="<?= $row['request_id'] ?>">
                                                <i class="bi bi-calendar text-blue"></i>
                                            </a>
                                            <?php if (!empty($row['expected_pickup_date'])) : ?>
                                                <a href="#" class="setDueDate" data-id="<?= $row['request_id'] ?>">
                                                    <i class="bi bi-calendar2-event text-purple"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <a href="#" class="viewRow" data-title="<?= htmlspecialchars($row['book_title']) ?>" data-author="<?= htmlspecialchars($row['author']) ?>" data-isbn="<?= htmlspecialchars($row['isbn']) ?>" data-publisher="<?= htmlspecialchars($row['publisher']) ?>" data-year="<?= htmlspecialchars($row['publication_year']) ?>" data-edition="<?= htmlspecialchars($row['edition']) ?>" data-quantity="<?= htmlspecialchars($row['quantity']) ?>" data-bookshelf="<?= htmlspecialchars($row['bookshelf_code']) ?>">
                                                <i class="bi bi-list text-green"></i>
                                            </a>
                                            <a href="#" class="deleteRow">
                                                <i class="bi bi-trash text-red"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="8">No book requests found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
    // Function to show success message
    function showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: message,
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload(); // Reload the page after confirmation
        });
    }

    // Function to show error message
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonText: 'OK'
        });
    }

    // View Book Details
    $('.viewRow').on('click', function () {
        Swal.fire({
            title: 'Book Details',
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
            confirmButtonText: 'Close'
        });
    });

    // Generic function for handling request actions
    function handleRequest(action, requestId, title, successMessage) {
        Swal.fire({
            title: `Confirm ${title}`,
            text: `Are you sure you want to ${title.toLowerCase()} this request?`,
            icon: action === 'reject' || action === 'delete' ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${title}`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('process_request.php', { request_id: requestId, action: action })
                    .done(response => {
                        let data = JSON.parse(response);
                        data.success ? showSuccess(successMessage) : showError(`Failed to ${title.toLowerCase()} the request.`);
                    })
                    .fail(() => showError('An error occurred. Please try again.'));
            }
        });
    }

    // Approve, Reject, and Delete actions
    $('.approveRow').on('click', function (e) {
        e.preventDefault();
        handleRequest('approve', $(this).data('id'), 'Approve', 'Request approved successfully.');
    });

    $('.rejectRow').on('click', function (e) {
        e.preventDefault();
        handleRequest('reject', $(this).data('id'), 'Reject', 'Request rejected successfully.');
    });

    $('.deleteRow').on('click', function (e) {
        e.preventDefault();
        let requestId = $(this).closest('tr').find('.request-id').text();
        handleRequest('delete', requestId, 'Delete', 'Request deleted successfully.');
    });

    // Function to set dates (pickup & due)
    function setDate(action, title, inputId, requestId) {
        Swal.fire({
            title: `Set ${title} Date`,
            html: `<input type="date" id="${inputId}" class="swal2-input">`,
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                let date = document.getElementById(inputId).value;
                if (!date) {
                    Swal.showValidationMessage('Please select a date');
                    return false;
                }
                return date;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('update_dates.php', { request_id: requestId, action: action, [inputId]: result.value })
                    .done(response => {
                        let data = JSON.parse(response);
                        data.success ? showSuccess(`${title} date updated!`) : showError('Failed to update.');
                    })
                    .fail(() => showError('An error occurred.'));
            }
        });
    }

    // Set Pickup and Due Dates
    $('.setPickupDate').on('click', function (e) {
        e.preventDefault();
        setDate('set_pickup_date', 'Pickup', 'pickup-date', $(this).data('id'));
    });

    $('.setDueDate').on('click', function (e) {
        e.preventDefault();
        setDate('set_due_date', 'Due', 'due-date', $(this).data('id'));
    });
});

</script>