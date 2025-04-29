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

        // First, check in the students table
        $stmt = $conn->prepare("SELECT student_id, password, role, status FROM students WHERE BINARY username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // User is a student
            $stmt->bind_result($student_id, $hashed_password, $role, $status);
            $stmt->fetch();

            if ($status == 0) {
                $error_message = "Your account has been blocked. Please contact the administrator.";
            } else if (password_verify($password, $hashed_password)) {
                // Valid student login
                session_regenerate_id(true);
                $_SESSION['user_logged_in'] = true;
                $_SESSION['student_id'] = $student_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['last_activity'] = time();
                header("Location: student_index.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            // Check in the admins table
            $stmt->close(); // Close previous statement
            $stmt = $conn->prepare("SELECT password, role FROM admins WHERE BINARY username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // User is an admin
                $stmt->bind_result($hashed_password, $role);
                $stmt->fetch();

                if (password_verify($password, $hashed_password)) {
                    // Valid admin login
                    session_regenerate_id(true);
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    $_SESSION['last_activity'] = time();
                    header("Location: admin_index.php");
                    exit();
                } else {
                    $error_message = "Invalid username or password.";
                }
            } else {
                $error_message = "Invalid username or password.";
            }
        }
        $stmt->close();
    } catch (Exception $e) {
        $error_message = "An error occurred. Please try again later.";
        error_log($e->getMessage());
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PSHS E-library Login</title>
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.min.css">
</head>
<style>
        /* Background styling with image */
        body.login-container {
            background: url('assets1/img/PILA1.jpg') no-repeat center center fixed;
            background-size: cover; /* Ensure the background image covers the entire page */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

       /* Transparent login box with blur effect */
.login-box {
    background: rgba(255, 255, 255, 0.8); /* White with transparency */
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;

    
}


        /* Logo styling */
        .login-logo img {
            width: 100px;
            display: block;
            margin: 0 auto 15px;
        }

       
    </style>
<body class="login-container">
    <form action="" method="POST">
        <div class="login-box">
            <div class="login-form">
                <a href="landingPage.php" class="login-logo">
                    <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="PSHS Logo" />
                </a>
                <div class="login-welcome">
                    Welcome back, <br />Please login to your <b>PSHS eLib</b> account.
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autocomplete="username" placeholder="Enter your username">
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label">Password</label>
                        <a href="forgot-password.html" class="btn-link ml-auto">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Enter your password">
                </div>

                <div class="login-form-actions">
                    <button type="submit" class="btn">
                        <span class="icon"><i class="bi bi-arrow-right-circle"></i></span>
                        Login
                    </button>
                </div>

                <div class="login-form-footer">
                    <div class="additional-link">
                        Don't have an account? <a href="signup.php">Signup</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/modernizr.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
