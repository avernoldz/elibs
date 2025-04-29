<?php
// Include the database connection file
include 'connection.php';

// Update SQL query to select additional columns from the books table
$sql = "SELECT br.request_id, br.request_date, br.status, br.expected_pickup_date, br.due_date, br.return_date, br.is_returned, 
               b.title AS book_title, b.author, b.isbn, b.publisher, b.publication_year, b.edition, b.quantity, b.bookshelf_code,
               s.name AS student_name
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id
        ";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Book Returned History</div>
        <!--
        <div class="m-0">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar2-range"></i>
                </span>
                <input type="text" class="form-control custom-daterange2">
            </div>
        </div>-->

        <div class="search-container">
            <button type="button" class="btn btn-success btn-icon me-2" onclick="window.location.href='admin_bookreturned.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Return Book">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table v-middle">
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
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>
                                    <div class='media-box'>
                                        <div class='media-box-body'>
                                            <div class='text-truncate'>" . htmlspecialchars($row['student_name']) . "</div>
                                        </div>
                                    </div>
                                </td>";
                            echo "<td>
                                    <div class='media-box'>
                                        <div class='media-box-body'>
                                            <div class='text-truncate text-capitalize'>" . htmlspecialchars($row['book_title']) . "</div>
                                        </div>
                                    </div>
                                </td>";
                            echo "<td>" . htmlspecialchars(date('m/d/Y', strtotime($row['request_date']))) . "</td>";
                            echo "<td>" . htmlspecialchars(!empty($row['return_date']) ? date('m/d/Y', strtotime($row['return_date'])) : '') . "</td>";

                            if ($row['is_returned']) {
                                echo "<td><span class='text-green td-status'><i class='bi bi-check-circle'></i> Returned</span></td>";
                                echo "<td>
                                        <div class='actions'>
                                            <a href='#' class='viewRow' data-bs-toggle='modal' data-bs-target='#viewRow' 
                                            data-title='" . htmlspecialchars($row['book_title']) . "' 
                                            data-author='" . htmlspecialchars($row['author']) . "' 
                                            data-isbn='" . htmlspecialchars($row['isbn']) . "' 
                                            data-publisher='" . htmlspecialchars($row['publisher']) . "' 
                                            data-year='" . htmlspecialchars($row['publication_year']) . "' 
                                            data-edition='" . htmlspecialchars($row['edition']) . "' 
                                            data-quantity='" . htmlspecialchars($row['quantity']) . "' 
                                            data-bookshelf='" . htmlspecialchars($row['bookshelf_code']) . "'>
                                            <i class='bi bi-list text-green'></i>
                                        </a>
                                        </div>
                                    </td>";
                            } else {
                                echo "<td><span class='text-red td-status'><i class='bi bi-clock-history'></i> Pending</span></td>";
                                echo "<td>
                                        <div class='actions'>
                                            <a href='#' class='viewRow' data-bs-toggle='modal' data-bs-target='#viewRow' 
                                            data-title='" . htmlspecialchars($row['book_title']) . "' 
                                            data-author='" . htmlspecialchars($row['author']) . "' 
                                            data-isbn='" . htmlspecialchars($row['isbn']) . "' 
                                            data-publisher='" . htmlspecialchars($row['publisher']) . "' 
                                            data-year='" . htmlspecialchars($row['publication_year']) . "' 
                                            data-edition='" . htmlspecialchars($row['edition']) . "' 
                                            data-quantity='" . htmlspecialchars($row['quantity']) . "' 
                                            data-bookshelf='" . htmlspecialchars($row['bookshelf_code']) . "'>
                                            <i class='bi bi-list text-green'></i>
                                        </a>
                                        </div>
                                    </td>";
                            }

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No book requests found</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal for setting pickup date -->
<div class="modal fade" id="setPickupDateModal" tabindex="-1" aria-labelledby="setPickupDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setPickupDateModalLabel">Set Expected Pickup Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="setPickupDateForm">
                    <input type="hidden" id="pickupRequestId" name="request_id">
                    <div class="mb-3">
                        <label for="expectedPickupDate" class="form-label">Expected Pickup Date</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar4"></i>
                            </span>
                            <input type="text" class="form-control datepicker-iso-week-numbers" id="expectedPickupDate" name="expected_pickup_date" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for setting due date -->
<div class="modal fade" id="setDueDateModal" tabindex="-1" aria-labelledby="setDueDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setDueDateModalLabel">Set Due Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="setDueDateForm">
                    <input type="hidden" id="dueRequestId" name="request_id">
                    <div class="mb-3">
                        <label for="dueDate" class="form-label">Due Date</label>
                        <input type="text" class="form-control datepicker" id="dueDate" name="due_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Success Modal -->
<div class="modal fade" id="actionSuccessModal" tabindex="-1" aria-labelledby="actionSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionSuccessModalLabel">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- This message will be set dynamically with JavaScript -->
                <p id="actionSuccessMessage">Action completed successfully.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="successModalButton">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="actionErrorModal" tabindex="-1" aria-labelledby="actionErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionErrorModalLabel">Error</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- This message will be set dynamically with JavaScript -->
                <p id="actionErrorMessage">An error occurred. Please try again later.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to approve this request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmApprove">Yes, Approve</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reject this request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Yes, Reject</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal View Row -->
<div class="modal modal-dark fade" id="viewRow" tabindex="-1" aria-labelledby="viewRowLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewRowLabel">View Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Book Title</h6>
                            <h5 id="modalBookTitle" class="text-capitalize">Title</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Author</h6>
                            <h5 id="modalBookAuthor" class="text-capitalize">Author</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>ISBN</h6>
                            <h5 id="modalBookIsbn" class="text-capitalize">ISBN</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Publisher</h6>
                            <h5 id="modalBookPublisher" class="text-capitalize">Publisher</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Publication Year</h6>
                            <h5 id="modalBookYear" class="text-capitalize">Year</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Edition</h6>
                            <h5 id="modalBookEdition" class="text-capitalize">Edition</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Quantity Available</h6>
                            <h5 id="modalBookQuantity">Quantity</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Bookshelf Code</h6>
                            <h5 id="modalBookShelf" class="text-capitalize">Shelf Code</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript to dynamically update modal content -->
<script>
    document.querySelectorAll('.viewRow').forEach(function(element) {
        element.addEventListener('click', function() {
            document.getElementById('modalBookTitle').textContent = this.getAttribute('data-title');
            document.getElementById('modalBookAuthor').textContent = this.getAttribute('data-author');
            document.getElementById('modalBookIsbn').textContent = this.getAttribute('data-isbn');
            document.getElementById('modalBookPublisher').textContent = this.getAttribute('data-publisher');
            document.getElementById('modalBookYear').textContent = this.getAttribute('data-year');
            document.getElementById('modalBookEdition').textContent = this.getAttribute('data-edition');
            document.getElementById('modalBookQuantity').textContent = this.getAttribute('data-quantity');
            document.getElementById('modalBookShelf').textContent = this.getAttribute('data-bookshelf');
        });
    });
</script>



<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // JavaScript to handle setPickupDate and setDueDate actions
    $(document).ready(function() {
        var requestId;

        function showSuccessModal(message) {
            $('#actionSuccessMessage').text(message);
            $('#actionSuccessModal').modal('show');
        }

        function showErrorModal(message) {
            $('#actionErrorMessage').text(message);
            $('#actionErrorModal').modal('show');
        }

        $('.setPickupDate').on('click', function() {
            requestId = $(this).data('id');
            $('#pickupRequestId').val(requestId);
        });

        $('.setDueDate').on('click', function() {
            requestId = $(this).data('id');
            $('#dueRequestId').val(requestId);
        });

        $('#setPickupDateForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'set_pickup_date.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#setPickupDateModal').modal('hide');
                    if (data.success) {
                        showSuccessModal('Pickup date set successfully.');
                    } else {
                        showErrorModal('Failed to set pickup date. Please try again.');
                    }
                },
                error: function() {
                    $('#setPickupDateModal').modal('hide');
                    showErrorModal('An error occurred. Please try again.');
                }
            });
        });

        $('#setDueDateForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'set_due_date.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#setDueDateModal').modal('hide');
                    if (data.success) {
                        showSuccessModal('Due date set successfully.');
                    } else {
                        showErrorModal('Failed to set due date. Please try again.');
                    }
                },
                error: function() {
                    $('#setDueDateModal').modal('hide');
                    showErrorModal('An error occurred. Please try again.');
                }
            });
        });
        // Approve action - Open modal and set request ID
        $('.approveRow').on('click', function(e) {
            e.preventDefault();
            requestId = $(this).data('id'); // Get request ID
            $('#approveModal').modal('show'); // Show Approve modal
        });

        // Confirm Approve action
        $('#confirmApprove').on('click', function() {
            $('#approveModal').modal('hide'); // Hide the modal
            $.ajax({
                url: 'process_request.php',
                type: 'POST',
                data: {
                    request_id: requestId,
                    action: 'approve'
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        showSuccessModal('Request approved successfully.');
                    } else {
                        showErrorModal('Failed to approve the request. Please try again.');
                    }
                },
                error: function() {
                    showErrorModal('An error occurred while approving the request. Please try again.');
                }
            });
        });

        // Reject action - Open modal and set request ID
        $('.rejectRow').on('click', function(e) {
            e.preventDefault();
            requestId = $(this).data('id'); // Get request ID
            $('#rejectModal').modal('show'); // Show Reject modal
        });

        // Confirm Reject action
        $('#confirmReject').on('click', function() {
            $('#rejectModal').modal('hide'); // Hide the modal
            $.ajax({
                url: 'process_request.php',
                type: 'POST',
                data: {
                    request_id: requestId,
                    action: 'reject'
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        showSuccessModal('Request rejected successfully.');
                    } else {
                        showErrorModal('Failed to reject the request. Please try again.');
                    }
                },
                error: function() {
                    showErrorModal('An error occurred while rejecting the request. Please try again.');
                }
            });
        });

        // Delete action - Open modal and set request ID
        $('.deleteRow').on('click', function(e) {
            e.preventDefault();
            requestId = $(this).closest('tr').find('.request-id').text(); // Get request ID from the row
            $('#deleteModal').modal('show'); // Show Delete modal
        });

        // Confirm Delete action
        $('#confirmDelete').on('click', function() {
            $('#deleteModal').modal('hide'); // Hide the modal
            $.ajax({
                url: 'delete_request.php',
                type: 'POST',
                data: {
                    request_id: requestId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        showSuccessModal('Request deleted successfully.');
                    } else {
                        showErrorModal('Failed to delete the request. Please try again.');
                    }
                },
                error: function() {
                    showErrorModal('An error occurred while deleting the request. Please try again.');
                }
            });
        });

        // Reload page when clicking OK on the success modal
        $('#successModalButton').on('click', function() {
            location.reload();
        });
    });
</script>