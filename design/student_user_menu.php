<?php


// Assuming the username is stored in a session variable after login
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Default to 'Guest' if not logged in
?>

<!-- Dropdown menu to display username -->
<li class="dropdown">
    <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
        <span class="user-name d-none d-md-block"><?php echo htmlspecialchars($username); ?></span>
        <span class="avatar">
            <img src="assets/images/user.png" alt="Student Avatar">
            <span class="status online"></span>
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
        <div class="header-profile-actions">
            <a href="profile.html">Profile</a>
            <a href="student_account_setting.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</li>
