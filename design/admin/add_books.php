<?php
session_start();
include 'connection.php';  // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    if (file_exists($target_file)) {
        $_SESSION['alert'] = ["type" => "warning", "message" => "File already exists!"];
        header("Location: admin_addbooks.php");
        exit();
    }

    if (move_uploaded_file($_FILES["book_image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, isbn, publisher, publication_year, edition, book_image_path, quantity, bookshelf_code) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisissis", $title, $author, $isbn, $publisher, $publication_year, $edition, $target_file, $quantity, $bookshelf_code);

        if ($stmt->execute()) {
            $_SESSION['alert'] = ["type" => "success", "message" => "Book added successfully!"];
        } else {
            if ($conn->errno == 1062) { // Duplicate ISBN error
                $_SESSION['alert'] = ["type" => "error", "message" => "Duplicate ISBN found!"];
            } else {
                $_SESSION['alert'] = ["type" => "error", "message" => "Database error occurred!"];
            }
        }
        $stmt->close();
    } else {
        $_SESSION['alert'] = ["type" => "error", "message" => "File upload failed!"];
    }

    $conn->close();
    header("Location: admin_addbooks.php");
    exit();
}
?>
