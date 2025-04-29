<?php
include 'connection.php';
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

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

    if ($stmt = $conn->prepare($sql)) {
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
                                        <a href='#' class='deleteRow' data-request-id='" . htmlspecialchars($row['request_id']) . "'>
                                            <i class='bi bi-trash text-red'></i>
                                        </a>
                                    </div>
                                </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No book requests found</td></tr>";
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
                <h5 class="modal-title">View Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $labels = [
                        "modalBookTitle" => "Book Title",
                        "modalBookAuthor" => "Author",
                        "modalBookIsbn" => "ISBN",
                        "modalBookPublisher" => "Publisher",
                        "modalBookYear" => "Publication Year",
                        "modalBookEdition" => "Edition",
                        "modalBookQuantity" => "Quantity Available",
                        "modalBookShelf" => "Bookshelf Code"
                    ];
                    foreach ($labels as $id => $label) {
                        echo "<div class='col-lg-4 col-sm-6 col-6'>
                                <div class='customer-card'>
                                    <h6>{$label}</h6>
                                    <h5 id='{$id}'>...</h5>
                                </div>
                            </div>";
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script Handlers -->
<script>
    document.querySelectorAll('.view-qr').forEach(el => {
        el.addEventListener('click', () => {
            document.getElementById('qr-img').src = el.getAttribute('data-qr');
        });
    });

    document.querySelectorAll('.viewRow').forEach(el => {
        el.addEventListener('click', () => {
            document.getElementById('modalBookTitle').textContent = el.getAttribute('data-title');
            document.getElementById('modalBookAuthor').textContent = el.getAttribute('data-author');
            document.getElementById('modalBookIsbn').textContent = el.getAttribute('data-isbn');
            document.getElementById('modalBookPublisher').textContent = el.getAttribute('data-publisher');
            document.getElementById('modalBookYear').textContent = el.getAttribute('data-year');
            document.getElementById('modalBookEdition').textContent = el.getAttribute('data-edition');
            document.getElementById('modalBookQuantity').textContent = el.getAttribute('data-quantity');
            document.getElementById('modalBookShelf').textContent = el.getAttribute('data-bookshelf');
        });
    });

    document.querySelectorAll('.deleteRow').forEach(el => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            const requestId = el.getAttribute('data-request-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the book request.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_request.php?request_id=' + requestId;
                }
            });
        });
    });
</script>
