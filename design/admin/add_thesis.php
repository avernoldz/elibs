<?php
// Database connection
include 'connection.php'; // Ensure this file contains the correct database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form inputs
    $title = $_POST['thesis_title'];
    $author = $_POST['author'];
    $advisor = $_POST['advisor'];
    $strand = $_POST['strand'];
    $completion_year = $_POST['completion_year'];
    $bookshelf_code = $_POST['bookshelf_code'];
    $abstract_image = $_FILES['abstract_image']; // Uploaded file

    // Check for duplicate thesis title
    $check_duplicate_query = "SELECT * FROM thesis WHERE title = ?";
    $stmt = $conn->prepare($check_duplicate_query);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: admin_addthesis.php?status=duplicate');
        exit();
    } else {
        // Handle file upload
        $target_dir = "assets/images/";
        $abstract_image_filename = basename($abstract_image['name']); // Get filename
        $target_file = $target_dir . $abstract_image_filename;
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($abstract_image['tmp_name']);
        if ($check === false) {
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Check file size (limit: 5MB)
        if ($abstract_image["size"] > 5000000) {
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Allow only JPG, PNG, JPEG formats
        if (!in_array($image_file_type, ['jpg', 'png', 'jpeg'])) {
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Upload the file
        if (move_uploaded_file($abstract_image['tmp_name'], $target_file)) {
            // Insert thesis into the database (store only filename)
            $insert_query = "INSERT INTO thesis (title, author, advisor, strand, completion_year, bookshelf_code, abstract_image) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssssiss", $title, $author, $advisor, $strand, $completion_year, $bookshelf_code, $abstract_image_filename);

            if ($stmt->execute()) {
                header('Location: admin_addthesis.php?status=success');
                exit();
            } else {
                header('Location: admin_addthesis.php?status=error');
                exit();
            }
        } else {
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
