<?php
// Include the database connection
include 'connection.php';

// Log errors to a file
function log_error($message) {
    $log_file = 'error_log.txt';
    $current_time = date('Y-m-d H:i:s');
    $log_message = "[$current_time] ERROR: $message\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

// Function to validate password strength
function is_strong_password($password) {
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    return preg_match($pattern, $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $fullname = htmlspecialchars($_POST['fullname']);
    $section = htmlspecialchars($_POST['section']);
    $grade_level = htmlspecialchars($_POST['grade_level']);
    $lrn = htmlspecialchars($_POST['lrn']);
    $birthday = htmlspecialchars($_POST['birthday']);

    // Validate that all fields are filled
    if (!empty($email) && !empty($username) && !empty($password) && !empty($fullname) && !empty($section) && !empty($grade_level) && !empty($lrn) && !empty($birthday)) {

        // Check password strength
        if (!is_strong_password($password)) {
            echo "<script>
                    $(document).ready(function(){
                        alert('Password must be 8+ characters with uppercase, lowercase, numbers, and special characters.');
                        $('#errorModal').modal('show');
                    });
                  </script>";
            exit();
        }

        try {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO students (email, username, password, name, section, grade_level, lrn, birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $email, $username, $hashed_password, $fullname, $section, $grade_level, $lrn, $birthday);

            if ($stmt->execute()) {
                echo "<script>
                        $(document).ready(function(){
                            $('#successModal').modal('show');
                            setTimeout(function(){
                                window.location.href = 'student_login.php';
                            }, 3000);
                        });
                      </script>";
            } else {
                // Log database error
                log_error("Database Insert Error: " . $conn->error);
                echo "<script>
                        $(document).ready(function(){
                            $('#errorModal').modal('show');
                        });
                      </script>";
            }
            $stmt->close();
        } catch (Exception $e) {
            // Log any other exceptions
            log_error("Exception: " . $e->getMessage());
            echo "<script>
                    $(document).ready(function(){
                        $('#errorModal').modal('show');
                    });
                  </script>";
        }
    } else {
        echo "<script>
                $(document).ready(function(){
                    $('#errorModal').modal('show');
                });
              </script>";
    }

    // Close the database connection
    $conn->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PSHS E-library Signup</title>

    <!-- Meta -->
		<meta name="description" content="Best Bootstrap Admin Dashboards">
		<meta name="author" content="Bootstrap Gallery" />
		<link rel="canonical" href="https://www.bootstrap.gallery/">
		<meta property="og:url" content="https://www.bootstrap.gallery">
		<meta property="og:title" content="Admin Templates - Dashboard Templates | Bootstrap Gallery">
		<meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
		<meta property="og:type" content="Website">
		<meta property="og:site_name" content="Bootstrap Gallery">
		<link rel="shortcut icon" href="assets/images/PSHS_LOGO-removebg-preview.png">
    <!-- *************
			************ Common Css Files *************
		************ -->

		<!-- Animated css -->
        <link rel="stylesheet" href="assets/css/animate.css">

        <!-- Bootstrap font icons css -->
        <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css">

        <!-- Main css -->
        <link rel="stylesheet" href="assets/css/main.min.css">

      
        <!-- Date Range CSS -->
		<link rel="stylesheet" href="assets/vendor/daterange/daterange.css">


    <!-- Custom styles to modify the form size -->
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
    max-width: 600px; /* Smaller width */
    
    
}

/* Responsive adjustments for smaller screens */
@media (max-width: 550px) {
    .login-box {
        padding: 15px;
        max-width: 80%; /* Use most of the screen width on small devices */
    }
}

  


/* Additional styling for improved aesthetics */
.login-logo img {
    width: 100px;
    display: block;
    margin: 0 auto 15px;
}
.container {
    display: flex;
    justify-content: center; /* Horizontally center the content */
    align-items: center; /* Vertically center the content (optional, already centered) */
}

.d-flex {
    flex-direction: row; /* Change to row for horizontal layout */
    align-items: center; /* Vertically align items within the flex container */
}

.login-welcome {
    margin-left: 20px; /* Adjust the margin to create desired spacing between logo and text */
}

    </style>
</head>
<body class="login-container">
    <form action="signup.php" method="POST">
        <div class="login-box">
            <div class="login-form">
                <!-- Container -->
                <div class="container text-center">
    <a href="landingPage.php" class="d-flex flex-column align-items-center justify-content-center text-decoration-none">
        <!-- Wrapper for logo and text -->
        <div class="d-flex align-items-center justify-content-center">
            <!-- Logo -->
            <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="PSHS E-Library" class="img-fluid" style="max-width: 100px;">
            
            <!-- Welcome Text -->
            <div class="login-welcome text-center ms-3"> <!-- Added margin start for spacing -->
                <h4 class="mb-0">Welcome to PSHS Elib</h4>
                <p class="mb-0">Please create your Student account.</p>
            </div>
        </div>
    </a>
</div>
                

                <!-- Start of the two-column layout -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="fullname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level" class="form-control" required>
                                <option value="" disabled selected>Select Grade Level</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        <div class="mb-3 m-0">
                        <label class="form-label">Birthday</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar4"></i>
                            </span>
                            <!-- Ensure name="birthday" is included here -->
                            <input type="text" name="birthday" class="form-control datepicker" required>
                        </div>
                    </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LRN (Learner Reference Number)</label>
                            <input type="text" name="lrn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <input type="text" name="section" class="form-control" required>
                        </div>
                        
                        
                        
                        <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">
                            Password 
                            <small id="password-strength" class="ms-2"></small> <!-- Moved next to the Password label -->
                        </label>
                    </div>
                    <input type="password" name="password" class="form-control" id="password" required>
                    <small id="password-help" class="text-muted" style="font-size: 12px;">
                        Password must be 8+ characters with uppercase, lowercase, numbers, and special characters.
                    </small>
                </div>
                    </div>
                </div>

                <!-- End of the two-column layout -->

                <div class="login-form-actions text-center">
                    <button type="submit" class="btn">
                        <span class="icon"><i class="bi bi-arrow-right-circle"></i></span> Signup
                    </button>
                </div>

                <div class="login-form-footer text-center mt-3">
                    <div class="additional-link">
                        Already have an account? <a href="login.php"> Login</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Signup Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You have successfully signed up! You will be redirected to the login page shortly.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Go to Login</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-Time Password Strength Feedback Script -->
    <script src="assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#password').on('input', function () {
                const password = $(this).val();
                const strengthMessage = checkPasswordStrength(password);
                const feedbackMessage = passwordFeedback(password);
                $('#password-strength').html(strengthMessage);
                $('#password-feedback').html(feedbackMessage);
            });

            // Toggle password visibility
            $('#togglePassword').on('click', function () {
                const passwordField = $('#password');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).find('i').toggleClass('bi-eye bi-eye-slash');
            });

            function checkPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/\d/)) strength++;
                if (password.match(/[\W_]/)) strength++;

                let message = '';
                switch (strength) {
                    case 1:
                    case 2:
                        message = '<span class="text-danger">Weak</span>';
                        break;
                    case 3:
                    case 4:
                        message = '<span class="text-warning">Moderate</span>';
                        break;
                    case 5:
                        message = '<span class="text-success">Strong</span>';
                        break;
                }
                return message;
            }

            function passwordFeedback(password) {
                let feedback = [];
                if (password.length < 8) feedback.push("At least 8 characters.");
                if (!password.match(/[A-Z]/)) feedback.push("An uppercase letter.");
                if (!password.match(/[a-z]/)) feedback.push("A lowercase letter.");
                if (!password.match(/\d/)) feedback.push("A number.");
                if (!password.match(/[\W_]/)) feedback.push("A special character.");

                if (feedback.length === 0) {
                    return "<span class='text-success'>Password meets all requirements.</span>";
                } else {
                    return "Password should include: " + feedback.join(", ");
                }
            }
        });
    </script>\
    
    <!-- *************
			************ Required JavaScript Files *************
		************* -->
		<!-- Required jQuery first, then Bootstrap Bundle JS -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/bootstrap.bundle.min.js"></script>
		<script src="assets/js/modernizr.js"></script>
		<script src="assets/js/moment.js"></script>

		<!-- *************
			************ Vendor Js Files *************
		************* -->
        <!-- Overlay Scroll JS -->
		<script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
		<script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>


		<!-- Main Js Required -->
		<script src="assets/js/main.js"></script>
    <!-- Date Range JS -->
		<script src="assets/vendor/daterange/daterange.js"></script>
		<script src="assets/vendor/daterange/custom-daterange.js"></script>
    

</body>
</html>
