<?php

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

<div class="card">
							<div class="card-header">
								<div class="card-title">Book Information</div>
							</div>
							<div class="card-body">

							<div class="card-border">
													<div class="card-border-title">General Information</div>
													<div class="card-border-body">
							<div class="card-body">
							
							<!-- Add Book Form -->
    <form method="POST" enctype="multipart/form-data" action="add_books.php">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="inputBookTitle" class="form-label">Book Title<span class="text-red">*</span></label>
                <input type="text" class="form-control" id="inputBookTitle" name="book_title" placeholder="Enter Book Title" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputAuthor" class="form-label">Author<span class="text-red">*</span></label>
                <input type="text" class="form-control" id="inputAuthor" name="author" placeholder="Enter Author" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputISBN" class="form-label">ISBN<span class="text-red">*</span></label>
                <input type="number" class="form-control" id="inputISBN" name="isbn" placeholder="Enter ISBN" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputPublisher" class="form-label">Publisher<span class="text-red">*</span></label>
                <input type="text" class="form-control" id="inputPublisher" name="publisher" placeholder="Enter Publisher" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputPublicationYear" class="form-label">Publication Year<span class="text-red">*</span></label>
                <input type="number" class="form-control" id="inputPublicationYear" name="publication_year" min="1800" max="2024" placeholder="Enter Publication Year" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputEdition" class="form-label">Edition<span class="text-red">*</span></label>
                <input type="text" class="form-control" id="inputEdition" name="edition" placeholder="Enter Edition">
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputQuantity" class="form-label">Quantity<span class="text-red">*</span></label>
                <input type="number" class="form-control" id="inputQuantity" name="quantity" min="1" placeholder="Enter Quantity" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="inputBookshelfCode" class="form-label">Bookshelf Code<span class="text-red">*</span></label>
                <input type="text" class="form-control" id="inputBookshelfCode" name="bookshelf_code" placeholder="Enter Bookshelf Code" required>
            </div>
            <div class="col-md-12 mb-3">
                <label for="bookImage" class="form-label">Upload Book Image</label>
                <input type="file" class="form-control" id="bookImage" name="book_image" accept="image/*" required>
            </div>
        </div>
        <div class="form-actions-footer">
            <div class="col-md-12 text-end">
                <button type="reset" class="btn btn-light">Cancel</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
							</div>
							</div>
							</div>
							</div>
						</div>
