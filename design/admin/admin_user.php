<?php
// Database connection
include 'connection.php';

// Fetch the admin's username and avatar
$query = "SELECT username, avatar FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$admin_id = 1; // Adjust as needed
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($adminUsername, $adminAvatar);
$stmt->fetch();
$stmt->close();

// Set a default avatar if none exists
$adminAvatar = !empty($adminAvatar) ? $adminAvatar : 'assets1/img/default-avatar.jpg';
?>

<!-- User Settings Dropdown -->
<a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
    <span class="user-name d-none d-md-block"><?php echo htmlspecialchars($adminUsername); ?></span>
    <span class="avatar">
        <img src="uploads/<?php echo htmlspecialchars($adminAvatar); ?>" alt="Admin Avatar">
        <span class="status online"></span>
    </span>
</a>