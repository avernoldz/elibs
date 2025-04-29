<?php

include 'connection.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $lrn = $_POST['lrn'];
    $dob = $_POST['dob'];
    $grade_level = $_POST['grade_level'];
    $section = $_POST['section'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['alert'] = "error";
        $_SESSION['alert_message'] = "Passwords do not match. Please try again!";
        header("Location: add_student.php");
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for duplicate LRN
    $checkQuery = $conn->prepare("SELECT COUNT(*) FROM students WHERE lrn = ?");
    $checkQuery->bind_param("s", $lrn);
    $checkQuery->execute();
    $checkQuery->bind_result($count);
    $checkQuery->fetch();
    $checkQuery->close();

    if ($count > 0) {
        $_SESSION['alert'] = "duplicate";
        header("Location: add_student.php");
        exit();
    }

    // Check for duplicate username
    $checkUsernameQuery = $conn->prepare("SELECT COUNT(*) FROM students WHERE username = ?");
    $checkUsernameQuery->bind_param("s", $username);
    $checkUsernameQuery->execute();
    $checkUsernameQuery->bind_result($usernameCount);
    $checkUsernameQuery->fetch();
    $checkUsernameQuery->close();

    if ($usernameCount > 0) {
        $_SESSION['alert'] = "error";
        $_SESSION['alert_message'] = "This username is already taken. Choose another one.";
        header("Location: add_student.php");
        exit();
    }

    // Insert student into the database
    $stmt = $conn->prepare("INSERT INTO students (name, email, lrn, birthday, grade_level, section, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        $_SESSION['alert'] = "error";
        $_SESSION['alert_message'] = "Failed to prepare the statement.";
        header("Location: add_student.php");
        exit();
    }

    $stmt->bind_param("ssssisss", $name, $email, $lrn, $dob, $grade_level, $section, $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['alert'] = "success";
    } else {
        $_SESSION['alert'] = "error";
        $_SESSION['alert_message'] = "An error occurred while adding the student.";
    }

    $stmt->close();
    $conn->close();

    header("Location: add_student.php"); // Redirect to prevent resubmission
    exit();
}
?>
<script>
    <?php if (isset($_SESSION['alert'])): ?>
        <?php if ($_SESSION['alert'] == "success"): ?>
            Swal.fire({
                title: "Success!",
                text: "Student added successfully!",
                icon: "success",
                confirmButtonColor: "#28a745",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "add_student.php";
            });
        <?php elseif ($_SESSION['alert'] == "duplicate"): ?>
            Swal.fire({
                title: "Error!",
                text: "A student with that LRN already exists.",
                icon: "error",
                confirmButtonColor: "#343a40",
                confirmButtonText: "Close"
            });
        <?php elseif ($_SESSION['alert'] == "error"): ?>
            Swal.fire({
                title: "Error!",
                text: "<?php echo $_SESSION['alert_message']; ?>",
                icon: "error",
                confirmButtonColor: "#343a40",
                confirmButtonText: "Close"
            });
        <?php endif; ?>

        <?php unset($_SESSION['alert']); unset($_SESSION['alert_message']); // Clear the session ?>
    <?php endif; ?>
</script>



<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card">
    <div class="card-header">
        <div class="card-title">Student Information</div>
    </div>
    <div class="card-body">
        <form method="POST" action="add_student.php">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="inputName" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="inputName" name="name" placeholder="Enter Student Name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputEmail" class="form-label">Email<span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter Email" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputLRN" class="form-label">LRN (Learner Reference Number)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="inputLRN" name="lrn" placeholder="Enter LRN" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputDOB" class="form-label">Date of Birth<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="inputDOB" name="dob" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputGradeLevel" class="form-label">Grade Level<span class="text-danger">*</span></label>
                    <select class="form-select" id="inputGradeLevel" name="grade_level" required>
                        <option value="">Select Grade Level</option>
                        <option value="7">Grade 7</option>
                        <option value="8">Grade 8</option>
                        <option value="9">Grade 9</option>
                        <option value="10">Grade 10</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputSection" class="form-label">Section<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="inputSection" name="section" placeholder="Enter Section" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputUsername" class="form-label">Username<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter Username" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputPassword" class="form-label">Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter Password" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" placeholder="Confirm Password" required>
                </div>
            </div>

            <div class="form-actions-footer text-end">
                <button type="reset" class="btn btn-light">Cancel</button>
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
</div>
