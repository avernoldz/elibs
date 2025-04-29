<?php
include 'connection.php';  // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $title = $_POST['book_title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $publication_year = $_POST['publication_year'];
    $edition = $_POST['edition'];
    $quantity = $_POST['quantity'];
    $bookshelf_code = $_POST['bookshelf_code'];





    // File upload logic
    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($_FILES["book_image"]["name"]);

    // Check if file already exists
    if (file_exists($target_file)) {
        echo '<script>window.location.href="add_books.php?status=file_exists";</script>';
        exit();
    }

    // Move uploaded file to the designated folder
    if (move_uploaded_file($_FILES["book_image"]["tmp_name"], $target_file)) {
        // Prepare and bind an SQL statement
        $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, publisher, publication_year, edition, book_image_path, quantity, bookshelf_code) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisissis", $title, $author, $isbn, $publisher, $publication_year, $edition, $target_file, $quantity, $bookshelf_code);

        // Execute the query
        if ($stmt->execute()) {
            echo '<script>window.location.href="admin_addbooks.php?status=success";</script>';
        } else {
            // Check if it's a duplicate ISBN
            if ($conn->errno == 1062) { // Duplicate entry error code
                echo '<script>window.location.href="admin_addbooks.php?status=duplicate";</script>';
            } else {
                echo '<script>window.location.href="admin_addbooks.php?status=error";</script>';
            }
        }

        // Close the statement
        $stmt->close();
    } else {
        echo '<script>window.location.href="admin_addbooks.php?status=file_upload_failed";</script>';
    }

    // Close the database connection
    $conn->close();
}
