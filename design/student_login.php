<?php
// Include database connection
include 'connection.php';
session_start();

// Define session timeout period (in seconds)
$session_timeout_duration = 1800; // 30 minutes

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize the username input to avoid XSS or injection attacks
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password']; // Password should not be sanitized, as it's hashed

        // Prepare the SQL statement with BINARY keyword to make username comparison case-sensitive
        $stmt = $conn->prepare("SELECT student_id, password, role, status FROM students WHERE BINARY username = ?");
        $stmt->bind_param("s", $username); // Bind the username parameter
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists in the database
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($student_id, $hashed_password, $role, $status);
            $stmt->fetch();


            // Check if the account is blocked
            if ($status == 0) {
                // If the status is 0, the account is blocked
                $error_message = "Your account has been blocked. Please contact the administrator.";
            } else {
                // Verify the password entered by the user
                if (password_verify($password, $hashed_password)) {
                    // Regenerate session ID to prevent session fixation attacks
                    session_regenerate_id(true);

                    // Set session variables, including role, student_id, and handle session timeout
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['student_id'] = $student_id; // Store the student_id in the session
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role; // Store the role in the session (e.g., 'student')
                    $_SESSION['last_activity'] = time(); // Record login time for session timeout

                    // Redirect to student dashboard
                    if ($role === 'student') {
                        header("Location: student_index.php");
                    } else {
                        // Optional: Handle cases where users with other roles try to log in
                        header("Location: unauthorized.php"); // A custom page for unauthorized access
                    }
                    exit(); // Ensure the script stops executing after redirection
                } else {
                    // Set error message if password is incorrect
                    $error_message = "Invalid username or password.";
                }
            }
        } else {
            // Set error message if username is not found
            $error_message = "Invalid username or password.";
        }

        // Close the statement after usage
        $stmt->close();
    } catch (Exception $e) {
        // Handle any potential database errors
        $error_message = "An error occurred. Please try again later.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PSHS E-library Student Login">
    <meta name="author" content="PSHS E-library" />
    <link rel="canonical" href="https://www.pshs.edu.ph/elib">
    <title>PSHS E-library Student Login</title>
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.min.css">
</head>

<body class="login-container">
    <form action="" method="POST">
        <div class="login-box">
            <div class="login-form">
                <a href="landingPage.php" class="login-logo">
                    <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="PSHS Logo" />
                </a>
                <div class="login-welcome">
                    Welcome back, <br />Please login to your <b>PSHS eLib</b> student account.
                </div>

                <!-- Display error message if login fails -->
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <!-- Username Input -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autocomplete="username" placeholder="Enter your username">
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">Password</label>
                        <a href="forgot-password.html" class="btn-link ml-auto">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Enter your password">
                </div>

                <!-- Submit Button -->
                <div class="login-form-actions">
                    <button type="submit" class="btn">
                        <span class="icon"><i class="bi bi-arrow-right-circle"></i></span>
                        Login
                    </button>
                </div>

                <!-- Signup Link -->
                <div class="login-form-footer">
                    <div class="additional-link">
                        Don't have an account? <a href="signup.php">Signup</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Required JavaScript Files -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/modernizr.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>