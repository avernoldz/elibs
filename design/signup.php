<?php
// Include the database connection
include 'connection.php';

// Function to validate password strength
function is_strong_password($password) {
    return preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $fullname = htmlspecialchars($_POST['fullname']);
    $section = htmlspecialchars($_POST['section']);
    $grade_level = htmlspecialchars($_POST['grade_level']);
    $lrn = htmlspecialchars($_POST['lrn']);
    $birthday = htmlspecialchars($_POST['birthday']);

    if (!empty($email) && !empty($username) && !empty($password) && !empty($fullname) && !empty($section) && !empty($grade_level) && !empty($lrn) && !empty($birthday)) {
        if (!is_strong_password($password)) {
            echo "<script>
                Swal.fire('Weak Password', 'Password must have uppercase, lowercase, number, and special character.', 'error');
            </script>";
            exit();
        }

        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO students (email, username, password, name, section, grade_level, lrn, birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $email, $username, $hashed_password, $fullname, $section, $grade_level, $lrn, $birthday);

            if ($stmt->execute()) {
                header("Location: signup.php?signup_success=1");
                exit();
            } else {
                echo "<script>Swal.fire('Signup Failed', 'An error occurred, try again.', 'error');</script>";
            }
            $stmt->close();
        } catch (Exception $e) {
            echo "<script>Swal.fire('Unexpected Error', '". addslashes($e->getMessage()) ."', 'error');</script>";
        }
    } else {
        echo "<script>Swal.fire('Missing Fields', 'Please fill in all required fields.', 'warning');</script>";
    }

    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PSHS E-library Signup</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/PSHS_LOGO-removebg-preview.png">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="assets/fonts/bootstrap/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/main.min.css">

    <!-- Date Range Picker -->
    <link rel="stylesheet" href="assets/vendor/daterange/daterange.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body.login-container {
            background: url('assets/images/DRONESHOT.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }
        @media (max-width: 550px) {
            .login-box { padding: 15px; max-width: 80%; }
        }
        .login-logo img { width: 100px; display: block; margin: 0 auto 15px; }
        .container { display: flex; justify-content: center; align-items: center; }
        .login-welcome { margin-left: 20px; }
    </style>
</head>
<body class="login-container">
    <form action="signup.php" method="POST">
        <div class="login-box">
            <div class="login-form">
                <div class="container text-center">
                    <a href="landingPage.php" class="d-flex align-items-center text-decoration-none">
                        <img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="PSHS E-Library" class="img-fluid" style="max-width: 100px;">
                        <div class="login-welcome text-center ms-3">
                            <h4 class="mb-0">Welcome to PSHS Elib</h4>
                            <p class="mb-0">Please create your Student account.</p>
                        </div>
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="fullname" class="form-control" required>

                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>

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

                        <label class="form-label">LRN</label>
                        <input type="text" name="lrn" class="form-control" required>


                        
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>

                        <label class="form-label">Birthday</label>
                        <input type="text" name="birthday" class="form-control datepicker" required>

                        <label class="form-label">Section</label>
                        <input type="text" name="section" class="form-control" required>

                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required>
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

    <!-- Handle SweetAlert Notifications -->
    <script>
        <?php if (isset($_GET['signup_success'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Signup Successful',
                text: 'You have successfully signed up! Redirecting to login...',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'login.php';
            });
        <?php } ?>
    </script>
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
