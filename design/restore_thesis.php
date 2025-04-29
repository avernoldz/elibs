<?php
include 'connection.php';

if (isset($_GET['archived_id'])) {
    $archivedId = (int)$_GET['archived_id'];

    // Fetch the archived record
    $stmt = $conn->prepare("SELECT * FROM archived_theses WHERE archived_id = ?");
    $stmt->bind_param("i", $archivedId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $thesis = $result->fetch_assoc();

        // Insert back into main thesis table
        $restoreStmt = $conn->prepare("
            INSERT INTO thesis (title, author, advisor, strand, completion_year, bookshelf_code, availability)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $restoreStmt->bind_param("ssssssi",
            $thesis['title'], $thesis['author'], $thesis['advisor'], $thesis['strand'],
            $thesis['completion_year'], $thesis['bookshelf_code'], $thesis['availability']
        );
        $restoreStmt->execute();

        // Remove from archive table
        $deleteStmt = $conn->prepare("DELETE FROM archived_theses WHERE archived_id = ?");
        $deleteStmt->bind_param("i", $archivedId);
        $deleteStmt->execute();

        header("Location: admin_archivedthesis.php?restored=1");
    } else {
        echo "Archived thesis not found.";
    }
} else {
    echo "Invalid request.";
}
?>
