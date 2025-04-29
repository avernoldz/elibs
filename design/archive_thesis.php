<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $thesisId = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM thesis WHERE id = ?");
    $stmt->bind_param("i", $thesisId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $thesis = $result->fetch_assoc();

        $archiveStmt = $conn->prepare("
            INSERT INTO archived_theses (thesis_id, title, author, advisor, strand, completion_year, bookshelf_code, availability)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $archiveStmt->bind_param("issssssi",
            $thesis['id'], $thesis['title'], $thesis['author'], $thesis['advisor'], $thesis['strand'],
            $thesis['completion_year'], $thesis['bookshelf_code'], $thesis['availability']
        );
        $archiveStmt->execute();

        $deleteStmt = $conn->prepare("DELETE FROM thesis WHERE id = ?");
        $deleteStmt->bind_param("i", $thesisId);
        $deleteStmt->execute();

        header("Location: admin_thesiscatalog.php?archived=1");
    } else {
        echo "Thesis not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
