<?php
include 'connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$request_status = $_SESSION['request_status'] ?? null;
unset($_SESSION['request_status']);

$books_per_page = 9;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search_query = $_GET['search'] ?? '';
$offset = ($current_page - 1) * $books_per_page;

if (!empty($search_query)) {
    $sql_count = "SELECT COUNT(*) as total_books FROM books WHERE title LIKE ?";
    $sql_books = "SELECT 
                    books.*, 
                    COALESCE(SUM(reviews.rating) / NULLIF(COUNT(reviews.rating), 0), 0) AS rating_total,
                    COUNT(reviews.rating) AS review_count
                  FROM books
                  LEFT JOIN reviews ON reviews.book_id = books.book_id
                  WHERE books.title LIKE ?
                  GROUP BY books.book_id
                  LIMIT ?, ?";

    $like_query = '%' . $search_query . '%';
    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);
    $stmt_count->bind_param('s', $like_query);
    $stmt_books->bind_param('sii', $like_query, $offset, $books_per_page);
} else {
    $sql_count = "SELECT COUNT(*) as total_books FROM books";
    $sql_books = "SELECT 
                    books.*, 
                    COALESCE(SUM(reviews.rating) / NULLIF(COUNT(reviews.rating), 0), 0) AS rating_total,
                    COUNT(reviews.rating) AS review_count
                  FROM books
                  LEFT JOIN reviews ON reviews.book_id = books.book_id
                  GROUP BY books.book_id
                  LIMIT ?, ?";

    $stmt_count = $conn->prepare($sql_count);
    $stmt_books = $conn->prepare($sql_books);
    $stmt_books->bind_param('ii', $offset, $books_per_page);
}

$stmt_count->execute();
$total_books = $stmt_count->get_result()->fetch_assoc()['total_books'];
$total_pages = ceil($total_books / $books_per_page);

$stmt_books->execute();
$result_books = $stmt_books->get_result();

if ($result_books->num_rows > 0) {
    while ($row = $result_books->fetch_assoc()) {
        $filledStars = round($row['rating_total']);
        echo '
        <div class="col-xxl-3 col-md-4 col-sm-6 col-12">
            <div class="product-card">
                <img class="product-card-img-top" src="' . $row["book_image_path"] . '" style="width: 100%; height: 250px; object-fit:fill;" alt="Book Image">
                <div class="product-card-body">
                    <h3 class="product-title text-capitalize">' . $row["title"] . '</h3>
                    <p class="book-author text-capitalize"><span style="color: #737373">Author:</span> ' . $row["author"] . '</p>
                    <p class="book-bookshelf"><span style="color: #737373">Shelf Code:</span> ' . $row["bookshelf_code"] . '</p>
                    <p class="book-bookshelf"><span style="color:#737373">Ratings:</span> 
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
                        <button class="btn btn-info btn-sm view-book-details" data-book=\'' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . '\'>Details</button>
                        <button class="btn btn-primary request-book-btn" 
                            data-book-id="' . $row["book_id"] . '" 
                            data-book-title="' . htmlspecialchars($row["title"], ENT_QUOTES) . '" 
                            data-book-author="' . htmlspecialchars($row["author"], ENT_QUOTES) . '" 
                            ' . (!$row["availability"] ? 'disabled' : '') . '>Request</button>
                    </div>
                </div>
            </div>
        </div>';
    }
} else {
    echo "<p>No books found.</p>";
}

if ($request_status) {
    echo '
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">' . ($request_status['type'] === 'success' ? 'Success' : 'Error') . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">' . $request_status['message'] . '</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>new bootstrap.Modal(document.getElementById("statusModal")).show();</script>';
}
?>

<!-- Book Details Modal -->
<div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-labelledby="bookDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookDetailsLabel">Book Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookDetailsBody"></div>
        </div>
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="viewReview" tabindex="-1" aria-labelledby="viewReviewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize">Ratings & Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark close-view" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Request Form -->
<form id="requestForm" method="POST" action="request.php" style="display: none;">
    <input type="hidden" name="book_id" id="requestBookId">
</form>

<style>
    #viewReview .modal-body {
        max-height: 50vh;
        overflow-y: auto;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // DETAILS (Bootstrap modal)
    document.querySelectorAll('.view-book-details').forEach(button => {
        button.addEventListener('click', () => {
            const book = JSON.parse(button.getAttribute('data-book'));
            document.getElementById('bookDetailsBody').innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <img src="${book.book_image_path}" class="img-fluid" style="max-height: 250px;">
                    </div>
                    <div class="col-md-8">
                        <h5>${book.title}</h5>
                        <p><strong>Author:</strong> ${book.author}</p>
                        <p><strong>ISBN:</strong> ${book.isbn}</p>
                        <p><strong>Publisher:</strong> ${book.publisher}</p>
                        <p><strong>Year:</strong> ${book.publication_year}</p>
                        <p><strong>Edition:</strong> ${book.edition}</p>
                        <p><strong>Quantity:</strong> ${book.quantity}</p>
                        <p><strong>Shelf Code:</strong> ${book.bookshelf_code}</p>
                        <p><strong>Status:</strong> <span class="badge ${book.availability == 1 ? 'bg-success' : 'bg-danger'}">${book.availability == 1 ? 'Available' : 'Unavailable'}</span></p>
                    </div>
                </div>`;
            new bootstrap.Modal(document.getElementById('bookDetailsModal')).show();
        });
    });

    // REVIEWS
    document.querySelectorAll('.review').forEach(button => {
        button.addEventListener('click', () => {
            const book_id = button.getAttribute('data-book-id');
            fetch('get_review_books.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `book_id=${book_id}`
            })
            .then(res => res.json())
            .then(data => {
                const body = document.querySelector('#viewReview .modal-body');
                body.innerHTML = '';
                if (data.status === 1 && Array.isArray(data.data)) {
                    data.data.forEach(review => {
                        body.innerHTML += `
                            <div class="review-item">
                                <div class="review-rating">${buildStars(review.rating)}</div>
                                <small style="color: #737373">${review.name} : ${review.created}</small>
                                <p>${review.review}</p>
                                <hr>
                            </div>`;
                    });
                } else {
                    body.innerHTML = '<p>No reviews found for this book yet.</p>';
                }
                new bootstrap.Modal(document.getElementById('viewReview')).show();
            })
            .catch(() => {
                Swal.fire("Error", "Failed to load reviews.", "error");
            });
        });
    });

    // REQUEST (SweetAlert)
    document.querySelectorAll('.request-book-btn').forEach(button => {
        button.addEventListener('click', () => {
            const bookId = button.getAttribute('data-book-id');
            const title = button.getAttribute('data-book-title');
            const author = button.getAttribute('data-book-author');

            Swal.fire({
                title: `Request "${title}"`,
                html: `Are you sure you want to request <strong>${title}</strong> by ${author}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Request',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('requestBookId').value = bookId;
                    document.getElementById('requestForm').submit();
                }
            });
        });
    });

    function buildStars(rating) {
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += `<span class="bi bi-star-fill ${i <= rating ? 'text-warning' : ''}"></span>`;
        }
        return stars;
    }
});
</script>

 <!-- SweetAlert integration inside student_bookcatalog.php -->
 <?php if (isset($_SESSION['request_status'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['request_status']['type'] ?>',
            title: '<?= $_SESSION['request_status']['type'] === 'success' ? 'Success' : 'Oops...' ?>',
            text: '<?= $_SESSION['request_status']['message'] ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['request_status']); ?>
<?php endif; ?>

<?php $conn->close(); ?>
