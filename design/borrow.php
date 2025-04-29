<?php
// Include database connection
include 'connection.php';

// Check if book_id is set
if (isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];

    // First, check the current quantity of the book
    $check_sql = "SELECT quantity FROM books WHERE book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        $current_quantity = $book['quantity'];

        // Check if the quantity is greater than zero
        if ($current_quantity > 0) {
            // Decrement the quantity by 1
            $new_quantity = $current_quantity - 1;

            // Set availability to 0 (unavailable) only if the quantity becomes 0
            $availability = ($new_quantity == 0) ? 0 : 1;

            // Update the quantity and availability in the database
            $update_sql = "UPDATE books SET quantity = ?, availability = ? WHERE book_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("iii", $new_quantity, $availability, $book_id);

            if ($update_stmt->execute()) {
                echo "Book borrowed successfully.";
            } else {
                echo "Error borrowing the book: " . $conn->error;
            }

            $update_stmt->close();
        } else {
            echo "Error: Book is out of stock.";
        }
    } else {
        echo "Error: Book not found.";
    }

    $check_stmt->close();
}

// Close the connection
$conn->close();

// Redirect back to the book list page (optional)
header("Location: admin_bookcatalog.php");
exit();
?>
