<?php
// Include the database connection file
include 'connection.php';

require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch book requests for the logged-in student (ensure student_id is in session)
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Fetch book requests with book title, image path, and due date for the logged-in student
    $sql = "SELECT 
            books.title, 
            books.book_image_path,  
            books.author,
            books.isbn,
            books.publisher,
            books.publication_year,
            books.edition,
            books.quantity,
            books.bookshelf_code,
            book_requests.request_id, 
            book_requests.request_date, 
            book_requests.status,
            book_requests.expected_pickup_date,
            book_requests.due_date
        FROM book_requests
        JOIN books ON book_requests.book_id = books.book_id
        WHERE book_requests.student_id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters and execute
        $stmt->bind_param('i', $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Failed to prepare the SQL statement: " . $conn->error;
        exit;
    }
} else {
    echo "Student is not logged in.";
    exit;
}
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">My Book Requests</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table v-middle">
                <thead>
                    <tr>
                        <th>Book Image</th>
                        <th>Book Title</th>
                        <th>Request Date</th>
                        <th>Status</th>
                        <th>Expected Pickup Date</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $qrCode = new QrCode($row['request_id']);
                            $writer = new PngWriter();
                            $qrCodeImage = $writer->write($qrCode);
                            $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCodeImage->getString());

                            echo "<tr>";
                            echo "<td>";
                            if (!empty($row['book_image_path'])) {
                                echo "<img src='" . htmlspecialchars($row['book_image_path']) . "' alt='Book Image' style='width: 50px; height: 50px; object-fit: cover;'>";
                            } else {
                                echo "No image available";
                            }
                            echo "</td>";

                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['request_date']) . "</td>";

                            if ($row['status'] == 'Approved') {
                                echo "<td><span class='text-green td-status'><i class='bi bi-check-circle'></i> Approved</span></td>";
                                echo "<td>" . htmlspecialchars($row['expected_pickup_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                            } elseif ($row['status'] == 'Pending') {
                                echo "<td><span class='text-blue td-status'><i class='bi bi-clock-history'></i> Pending</span></td>";
                                echo "<td>" . htmlspecialchars($row['expected_pickup_date']) . "</td>";
                                echo "<td>-</td>";
                            } else {
                                echo "<td><span class='text-red td-status'><i class='bi bi-x-circle'></i> Rejected</span></td>";
                                echo "<td>-</td>";
                                echo "<td>-</td>";
                            }

                            echo "<td>
                                    <div class='actions'>
                                        <a href='#' class='viewRow' data-bs-toggle='modal' data-bs-target='#viewRow' 
                                        data-title='" . htmlspecialchars($row['title']) . "' 
                                        data-author='" . htmlspecialchars($row['author']) . "' 
                                        data-isbn='" . htmlspecialchars($row['isbn']) . "' 
                                        data-publisher='" . htmlspecialchars($row['publisher']) . "' 
                                        data-year='" . htmlspecialchars($row['publication_year']) . "' 
                                        data-edition='" . htmlspecialchars($row['edition']) . "' 
                                        data-quantity='" . htmlspecialchars($row['quantity']) . "' 
                                        data-bookshelf='" . htmlspecialchars($row['bookshelf_code']) . "'>
                                            <i class='bi bi-list text-green'></i>
                                        </a>
                                        <a href='#' class='view-qr' data-bs-toggle='modal' data-bs-target='#viewQRCode' 
                                            data-qr='" . $qrCodeDataUri . "'>
                                            <i class='bi bi-eye text-secondary'></i>
                                        </a>

                                        <a href='#' class='deleteRow' data-bs-toggle='modal' data-bs-target='#deleteConfirmationModal' 
                                            data-request-id='" . htmlspecialchars($row['request_id']) . "'>
                                            <i class='bi bi-trash text-red'></i>
                                            </a>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No book requests found</td>";
                    }

                    $result->free();
                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
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
                            <h5 id="modalBookTitle">Title</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Author</h6>
                            <h5 id="modalBookAuthor">Author</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>ISBN</h6>
                            <h5 id="modalBookIsbn">ISBN</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Publisher</h6>
                            <h5 id="modalBookPublisher">Publisher</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Publication Year</h6>
                            <h5 id="modalBookYear">Year</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-6">
                        <div class="customer-card">
                            <h6>Edition</h6>
                            <h5 id="modalBookEdition">Edition</h5>
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
                            <h5 id="modalBookShelf">Shelf Code</h5>
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

<div class="modal modal-dark fade" id="viewQRCode" tabindex="-1" aria-labelledby="viewQRCode" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewQRCodes">View QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <img src="" alt="QR Code" id="qr-img" width="250px" height="auto">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this book request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript to dynamically update modal content -->
<script>
    document.querySelectorAll('.view-qr').forEach(function(element) {
        element.addEventListener('click', function() {
            document.getElementById('qr-img').src = this.getAttribute('data-qr');
        });
    });

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

    document.querySelectorAll('.deleteRow').forEach(function(element) {
        element.addEventListener('click', function() {
            // Retrieve the request ID from the data attribute
            var requestId = this.getAttribute('data-request-id');

            // Update the confirm button link with the delete URL containing the request_id
            document.getElementById('confirmDeleteButton').setAttribute('href', 'delete_request.php?request_id=' + requestId);
        });
    });
</script>