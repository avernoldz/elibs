<?php
// Include database connection
include 'connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get student LRN (Unique Identifier)
    $lrn = trim($_POST['lrn']);

    if (empty($lrn)) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'LRN is required to update student information.',
                icon: 'error'
            }).then(() => { window.history.back(); });
        </script>";
        exit();
    }

    // Initialize update fields
    $updates = [];
    $params = [];
    $types = "";

    // Check each field and update only if provided
    if (!empty($_POST['email'])) {
        $updates[] = "email = ?";
        $params[] = trim($_POST['email']);
        $types .= "s";
    }
    if (!empty($_POST['grade_level'])) {
        $updates[] = "grade_level = ?";
        $params[] = trim($_POST['grade_level']);
        $types .= "s";
    }
    if (!empty($_POST['section'])) {
        $updates[] = "section = ?";
        $params[] = trim($_POST['section']);
        $types .= "s";
    }

    // Proceed only if there are fields to update
    if (!empty($updates)) {
        // Prepare SQL query
        $sql = "UPDATE students SET " . implode(", ", $updates) . " WHERE lrn = ?";
        $params[] = $lrn; // Add LRN at the end
        $types .= "s"; // Assuming LRN is stored as a string

        // Prepare statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param($types, ...$params);

            // Execute query
            if ($stmt->execute()) {
                echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Student updated successfully.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'admin_studentlist.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update student. Please try again.',
                        icon: 'error'
                    }).then(() => { window.history.back(); });
                </script>";
            }

            $stmt->close();
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Database error: Failed to prepare statement.',
                    icon: 'error'
                }).then(() => { window.history.back(); });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'No Changes!',
                text: 'No fields were updated.',
                icon: 'info'
            }).then(() => {
                window.location.href = 'admin_studentlist.php';
            });
        </script>";
    }
}

// Close database connection
$conn->close();
?>
