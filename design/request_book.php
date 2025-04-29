<?php
// request_book.php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
    $student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;

    if ($book_id <= 0 || $student_id <= 0) {
        http_response_code(400);
        echo "Invalid book or student ID.";
        exit;
    }

    // Check book availability
    $stmt = $conn->prepare("SELECT quantity FROM books WHERE book_id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $stmt->bind_result($quantity);
    $stmt->fetch();
    $stmt->close();

    if ($quantity <= 0) {
        http_response_code(409);
        echo "Book is currently unavailable.";
        exit;
    }

    // Insert book request into borrowed_books table
    $stmt = $conn->prepare("INSERT INTO borrowed_books (student_id, book_id, borrow_date, status) VALUES (?, ?, NOW(), 'borrowed')");
    $stmt->bind_param("ii", $student_id, $book_id);
    if ($stmt->execute()) {
        // Reduce quantity in books table
        $updateStmt = $conn->prepare("UPDATE books SET quantity = quantity - 1 WHERE book_id = ?");
        $updateStmt->bind_param("i", $book_id);
        $updateStmt->execute();
        $updateStmt->close();

        echo "Book request submitted successfully.";
    } else {
        http_response_code(500);
        echo "Failed to insert book request.";
    }

    $stmt->close();
} else {
    http_response_code(405);
    echo "Invalid request method.";
}

$conn->close();
