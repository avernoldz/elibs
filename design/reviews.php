<?php
include 'connection.php';
?>

<!-- Content wrapper start -->
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Student Reviews</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Book Details</th>
                                    <th>Date</th>
                                    <th>Review</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT students.avatar AS student_avatar, students.name AS student_name, students.lrn, 
                                               books.book_image_path AS book_image, books.title AS book_title, books.isbn, 
                                               reviews.rating, reviews.created, reviews.review 
                                        FROM reviews 
                                        JOIN students ON reviews.student_id = students.student_id 
                                        JOIN books ON reviews.book_id = books.book_id";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>
                                                    <div class='media-box'>
                                                        <img src='" . $row['student_avatar'] . "' class='media-avatar' alt='Bootstrap Gallery'>
                                                        <div class='media-box-body'>
                                                            <a href='#' class='text-truncate'>" . $row['student_name'] . "</a>
                                                            <p>LRN: " . $row['lrn'] . "</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class='media-box'>
                                                        <img src='" . $row['book_image'] . "' class='media-avatar-lg' alt='Product'>
                                                        <div class='media-box-body'>
                                                            <a href='#' class='text-truncate'>" . $row['book_title'] . "</a>
                                                            <p>ISBN: " . $row['isbn'] . "</p>
                                                            <div class='rating-block'>
                                                                <div class='rate" . $row['rating'] . "'></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>" . $row['created'] . "</td>
                                                <td>" . $row['review'] . "</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No reviews found.</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content wrapper end -->
