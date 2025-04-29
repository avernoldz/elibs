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
            books.book_id, 
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
            book_requests.due_date,
            book_requests.return_date,
            book_requests.is_returned
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

<style>
    .rating {
        display: flex;
        justify-content: center;
        font-size: 2em;
    }

    .star {
        cursor: pointer;
        color: #ccc;
        transition: color 0.3s ease;
    }

    .star:hover,
    .star.selected {
        color: #FFD700;
        /* Gold color for the selected stars */
    }

    .star.selected {
        color: #FFD700;
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="card-title">My Returned Books</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table v-middle">
                <thead>
                    <tr>
                        <th>Book Image</th>
                        <th>Book Title</th>
                        <th>Request Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $qrCode = new QrCode($row['isbn']);
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

                            echo "<td class='text-capitalize'>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars(date('m/d/Y', strtotime($row['request_date']))) . "</td>";
                            echo "<td>" . htmlspecialchars(!empty($row['return_date']) ? date('m/d/Y', strtotime($row['return_date'])) : '') . "</td>";

                            if ($row['is_returned']) {
                                echo "<td><span class='text-green td-status'><i class='bi bi-check-circle'></i> Returned</span></td>";

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

                                                <a href='#' class='rate' data-bs-toggle='modal' data-bs-target='#rateBook' 
                                                    data-book-id='" . $row['book_id'] . "'>
                                                    <i class='bi bi-star-fill text-warning'></i>
                                                </a>
                                            </div>
                                        </td>";
                                echo "</tr>";
                            } else {
                                echo "<td><span class='text-red td-status'><i class='bi bi-clock-history'></i> Pending</span></td>";

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
                                        </div>
                                    </td>";
                                echo "</tr>";
                            }
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

<div class="modal modal-dark fade" id="rateBook" tabindex="-1" aria-labelledby="rateBook" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xs modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rateBooks">Rate Book</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form action="" id="form">
                        <div class="col-md-12">

                            <div class="mb-3">
                                <label for="rate" class="form-label">Rate</label>
                                <div id="rate" class="rating">
                                    <span class="star" data-value="1">&#9733;</span>
                                    <span class="star" data-value="2">&#9733;</span>
                                    <span class="star" data-value="3">&#9733;</span>
                                    <span class="star" data-value="4">&#9733;</span>
                                    <span class="star" data-value="5">&#9733;</span>
                                </div>
                                <input type="hidden" id="starRating" name="rating">
                                <input type="hidden" id="book-id" name="book-id">
                            </div>
                            <div class="mb-3">
                                <label for="review" class="form-label">Review</label>
                                <textarea class="form-control" id="review" rows="5"></textarea>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="submit-rate">Save</button>
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
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.rate').forEach(function(element) {
            element.addEventListener('click', function() {
                document.getElementById('book-id').value = this.getAttribute('data-book-id');
            });
        });

        document.querySelectorAll('.star').forEach(function(star) {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');

                // Set the selected rating value in the hidden input
                document.getElementById('starRating').value = value;

                // Update the star selection visually
                document.querySelectorAll('.star').forEach(function(star) {
                    star.classList.remove('selected'); // Remove previous selection
                });

                // Add 'selected' class to the clicked stars
                for (let i = 0; i < value; i++) {
                    document.querySelectorAll('.star')[i].classList.add('selected');
                }
            });
        });

        $('#submit-rate').click(function(event) {
            event.preventDefault();

            // Get the values of the rating, review, and book_id
            const rating = $('#starRating').val();
            const comments = $('#review').val();
            const book_id = $('#book-id').val();

            // Validate input (optional)
            if (!rating || !comments || !book_id) {
                Swal.fire({
                    title: "Error",
                    text: "Please fill in all fields.",
                    icon: "error"
                });
                return; // Stop execution if any field is empty
            }

            // Call saveReview with the data
            saveReview(book_id, rating, comments);

        });

        function saveReview(book_id, rating, comments) {
            const url = 'save_review_books.php';

            // Prepare the data to be sent
            const postData = new URLSearchParams();
            postData.append('book_id', book_id);
            postData.append('rating', rating);
            postData.append('review', comments);

            // Send the AJAX request using fetch
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: postData
                })
                .then(response => response.json())
                .then(responseData => {
                    if (responseData.status === 1) {
                        // Review saved successfully
                        Swal.fire({
                            title: "Success",
                            text: "Review has been saved.",
                            icon: "success"
                        });

                        $('#rateBook').modal('hide');
                    } else if (responseData.status === 2) {
                        // Error saving review
                        Swal.fire({
                            title: "Error",
                            text: "There is an error saving your review.",
                            icon: "error"
                        });
                    } else {
                        // Unexpected response
                        Swal.fire({
                            title: "Error",
                            text: "Unexpected response from server.",
                            icon: "error"
                        });
                        console.log("Unexpected response:", responseData);
                    }
                })
                .catch(error => {
                    // Handle any errors that occur during the request
                    console.error('Error:', error);
                    Swal.fire({
                        title: "Error",
                        text: "There was an error submitting the review.",
                        icon: "error"
                    });
                });
        }


        document.querySelectorAll('.deleteRow').forEach(function(element) {
            element.addEventListener('click', function() {
                // Retrieve the request ID from the data attribute
                var requestId = this.getAttribute('data-request-id');

                // Update the confirm button link with the delete URL containing the request_id
                document.getElementById('confirmDeleteButton').setAttribute('href', 'delete_request.php?request_id=' + requestId);
            });
        });
    });
</script>