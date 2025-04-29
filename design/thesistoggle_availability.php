<?php
include 'connection.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Set availability based on action
    $availability = ($action === 'activate') ? 1 : 0;

    // Update the availability in the database
    $stmt = $conn->prepare("UPDATE thesis SET availability = ? WHERE id = ?");
    $stmt->bind_param("ii", $availability, $id);

    if ($stmt->execute()) {
        header("Location: admin_thesiscatalog.php?status=success");
    } else {
        header("Location: admin_thesiscatalog.php?status=error");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin_thesiscatalog.php?status=invalid_request");
}
?>
