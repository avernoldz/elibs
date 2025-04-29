<?php
// Database connection
include 'connection.php'; // Assuming you have a connection.php for connecting to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form inputs
    $title = $_POST['thesis_title'];
    $author = $_POST['author'];
    $advisor = $_POST['advisor'];
    $strand = $_POST['strand'];
    $completion_year = $_POST['completion_year'];
    $bookshelf_code = $_POST['bookshelf_code'];
    $abstract_image = $_FILES['abstract_image'];

    // Check for duplicate thesis title
    $check_duplicate_query = "SELECT * FROM thesis WHERE title = ?";
    $stmt = $conn->prepare($check_duplicate_query);
    $stmt->bind_param("s", $title);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Redirect to the same page with a "duplicate" flag
        header('Location: admin_addthesis.php?status=duplicate');
        exit();
    } else {
        // Handle file upload
        $target_dir = "assets/images/";
        $target_file = $target_dir . basename($abstract_image['name']);
        $upload_ok = 1;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($abstract_image['tmp_name']);
        if ($check === false) {
            // Redirect to the same page with a "file_error" flag
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Check file size (e.g., max 5MB)
        if ($abstract_image["size"] > 5000000) {
            // Redirect to the same page with a "file_error" flag
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Allow only certain file formats
        if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            // Redirect to the same page with a "file_error" flag
            header('Location: admin_addthesis.php?status=file_error');
            exit();
        }

        // Upload the file if everything is ok
        if ($upload_ok == 1) {
            if (move_uploaded_file($abstract_image['tmp_name'], $target_file)) {
                // Store only the filename in the database
                $abstract_image_filename = basename($abstract_image['name']); // Get only the filename

                // Insert thesis into the database
                $insert_query = "INSERT INTO thesis (title, author, advisor, strand, completion_year, bookshelf_code, abstract_image) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ssssiss", $title, $author, $advisor, $strand, $completion_year, $bookshelf_code, $abstract_image_filename); // Use the filename only

                if ($stmt->execute()) {
                    // Redirect to the same page with a "success" flag
                    header('Location: admin_addthesis.php?status=success');
                    exit();
                } else {
                    // Redirect to the same page with an "error" flag
                    header('Location: admin_addthesis.php?status=error');
                    exit();
                }
            } else {
                // Redirect to the same page with a "file_error" flag
                header('Location: admin_addthesis.php?status=file_error');
                exit();
            }
        }
    }

    $stmt->close();
    $conn->close();
}
?>
