<?php
// Include the database connection file
include 'connection.php';


// Check for request status
$request_status = isset($_SESSION['request_status']) ? $_SESSION['request_status'] : null;
unset($_SESSION['request_status']);

// Number of books to display per page
$books_per_page = 9;

// Get the current page from the URL, if not set, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Get the search query, if available
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL based on whether a search query is present
if (!empty($search_query)) {
    $sql_count = "SELECT COUNT(*) as total_books FROM books WHERE title LIKE ?";
    // $sql_books = "SELECT * FROM books WHERE title LIKE ? LIMIT ?, ?";
    $sql_books = "SELECT 
                    books.*, 
                    COALESCE(SUM(reviews.rating) / NULLIF(COUNT(reviews.rating), 0), 0) * 5 / 5 AS rating_total,
                    COUNT(reviews.rating) AS review_count
                FROM 
                    books
                LEFT JOIN 
                    reviews ON reviews.book_id = books.book_id
                WHERE books.title LIKE ?
                GROUP BY 
                    books.book_id
                LIMIT ?, ?";

    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);

    $like_query = '%' . $search_query . '%';
    $stmt_count->bind_param('s', $like_query);
    $stmt_books->bind_param('sii', $like_query, $offset, $books_per_page);
} else {
    $sql_count = "SELECT COUNT(*) as total_books FROM books";
    $sql_books = "SELECT 
                    books.*, 
                    COALESCE(SUM(reviews.rating) / NULLIF(COUNT(reviews.rating), 0), 0) * 5 / 5 AS rating_total,
                    COUNT(reviews.rating) AS review_count
                FROM 
                    books
                LEFT JOIN 
                    reviews ON reviews.book_id = books.book_id
                
                GROUP BY 
                    books.book_id
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
$offset = ($current_page - 1) * $books_per_page;

$stmt_books->execute();
$result_books = $stmt_books->get_result();

if ($result_books->num_rows > 0) {
    while ($row = $result_books->fetch_assoc()) {

        $filledStars =  round($row['rating_total']);

        echo '
        
        <div class="col-xxl-3 col-md-4 col-sm-6 col-12">
            <div class="product-card">
                <img class="product-card-img-top" src="' . $row["book_image_path"] . '" alt="Book Image" style="width: 100%; height: 250px; object-fit:fill;" alt="Bootstrap Gallery">
                <div class="product-card-body">
                    <h3 class="product-title text-capitalize">' . $row["title"] . '</h3>
                    <p class="book-author text-capitalize"><span style="color: #737373">Author:</span> ' . $row["author"] . ' </p>                    
                    <p class="book-bookshelf"><span style="color: #737373">Shelf Code: </span>' . $row["bookshelf_code"] . '</p>
                    <p class="book-bookshelf"><span style="color:#737373">Ratings: </span> 
                        <span style="font-size: .7rem;">';

        for ($i = 1; $i <= 5; $i++) {
            $starClass = $i <= $filledStars ? 'text-warning' : '';
            echo '<span class="bi bi-star-fill ' . $starClass . '"></span>';
        }

        echo '<span> | (' . $row['review_count'] . ') <span class="text-primary review" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewReview" data-book-id="' . $row['book_id'] . '">View more reviews</span></span>
                        </span>
                    </p>

                    <hr style="border-top:1px solid rgb(233, 233, 233);">
                    <div class="d-flex justify-content-between">
                    

                        <span class="d-flex align-items-center btn btn-sm ' . ($row["availability"] ? 'btn-outline-success' : 'btn-outline-danger') . '">
                            ' . ($row["availability"] ? 'Available' : 'Unavailable') . '
                        </span>

                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal' .  $row["book_id"] . '"' . (!$row["availability"] ? 'disabled' : '') . '>Request</button>
                        
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Modal for Book Request -->
        <div class="modal fade" id="requestModal' . $row["book_id"] . '" tabindex="-1" aria-labelledby="requestModalLabel' . $row["book_id"] . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel' . $row["book_id"] . '">Request Book: ' . $row["title"] . '</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to request this book: <strong>' . $row["title"] . '</strong> by ' . $row["author"] . '?
                    </div>
                    <div class="modal-footer">
                        <form action="request.php" method="post">
                            <input type="hidden" name="book_id" value="' . $row["book_id"] . '">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Confirm Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        ';
    }
} else {
    echo "No books found.";
}

// Display the modal if there's a request status message
if ($request_status) {
    echo '
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">' . ($request_status['type'] === 'success' ? 'Success' : 'Error') . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ' . $request_status['message'] . '
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Automatically show the status modal when the page loads
    var statusModal = new bootstrap.Modal(document.getElementById("statusModal"));
    statusModal.show();
    </script>
    ';
}

?>
<style>
    #viewReview .modal-body {
        max-height: 50vh;
        /* Adjust the height to fit your design */
        overflow-y: auto;
    }
</style>
<!-- Modal for viewing review -->
<div class="modal fade" id="viewReview" tabindex="-1" aria-labelledby="viewReviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="viewReviewLabel">Ratings & Reviews </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form action="request.php" method="post">
                    <input type="hidden" name="book_id" value="">
                    <button type="button" class="btn btn-dark close-view" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('.review').click(function() {
            const book_id = this.getAttribute('data-book-id');
            saveReview(book_id);
        });

        $('.close-view').click(function() {
            $('.modal-backdrop.fade.show').hide();
        });

        function saveReview(book_id) {
            const url = 'get_review_books.php';

            // Prepare the data to be sent
            const postData = new URLSearchParams();
            postData.append('book_id', book_id);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: postData
                })
                .then(response => response.json())
                .then(responseData => {
                    const modalBody = document.querySelector('#viewReview .modal-body');
                    modalBody.innerHTML = ''; // Clear existing content in the modal

                    if (responseData.status === 1 && Array.isArray(responseData.data) && responseData.data.length > 0) {
                        const reviews = responseData.data;

                        // Loop through the reviews and append them to the modal
                        reviews.forEach(review => {
                            const reviewElement = document.createElement('div');
                            reviewElement.classList.add('review-item'); // Style this class as needed

                            // Build the review HTML
                            reviewElement.innerHTML = `
                    <div class="review-rating">
                        ${buildStars(review.rating)} <!-- Dynamically render stars -->
                    </div>
                    <div class="review-date">
                        <small style="color: #737373">${review.name} : ${review.created}</small>
                    </div>
                    <div class="review-text">
                        <p>${review.review}</p>
                    </div>
                    <hr style="border-top:1px solid rgb(233, 233, 233);">
                `;

                            // Append the review to the modal body
                            modalBody.appendChild(reviewElement);
                        });
                    } else {
                        // Handle case where no reviews are found
                        const noReviewsElement = document.createElement('div');
                        noReviewsElement.classList.add('review-item');
                        noReviewsElement.innerHTML = `
                <div class="review-rating text-center">
                    No reviews found for this book yet.
                </div>
            `;
                        modalBody.appendChild(noReviewsElement);
                    }

                    // Show the modal with reviews
                    const reviewModal = new bootstrap.Modal(document.getElementById('viewReview'));
                    reviewModal.show();
                })
                .catch(error => {
                    // Handle any errors that occur during the request
                    console.error('Error:', error);
                    Swal.fire({
                        title: "Error",
                        text: "There was an error retrieving the reviews.",
                        icon: "error"
                    });
                });
        }

        // Helper function to render stars dynamically based on the rating
        function buildStars(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<span class="bi bi-star-fill ${i <= rating ? 'text-warning' : ''}"></span>`;
            }
            return stars;
        }


    });
</script>
<?php
$conn->close();
