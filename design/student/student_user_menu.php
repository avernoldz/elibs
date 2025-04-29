<?php
// Include database connection
include 'connection.php';

// Ensure session is started only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default values
$studentUsername = 'Guest';
$studentAvatar = 'assets1/img/default-avatar.jpg'; // Default avatar

// Check if the student is logged in
if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    // Fetch student's username and avatar from database
    $query = "SELECT username, avatar FROM students WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($studentUsername, $studentAvatarPath);
        $stmt->fetch();

        // Use student's avatar if available, else use default
        if (!empty($studentAvatarPath)) {
            $studentAvatar = $studentAvatarPath;
        }
    }
    $stmt->close();
}
?>

<!-- User Settings Dropdown for Students -->
<a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
    <span class="user-name d-none d-md-block"><?php echo htmlspecialchars($studentUsername); ?></span>
    <span class="avatar">
        <img src="<?php echo htmlspecialchars($studentAvatar); ?>" alt="Student Avatar">
        <span class="status online"></span>
    </span>
</a>
