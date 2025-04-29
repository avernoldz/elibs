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

    // Form validation for password match
    if ($password !== $confirm_password) {
        echo '<script>window.location.href="admin_addstudent.php?error=password_mismatch";</script>';
        exit();
    }

    // Hash the password for secure storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the query to check for duplicate LRN
    $checkQuery = $conn->prepare("SELECT COUNT(*) FROM students WHERE lrn = ?");
    $checkQuery->bind_param("s", $lrn);
    
    if (!$checkQuery->execute()) {
        echo "Error executing check for LRN: " . $checkQuery->error;
        exit();
    }
    
    $checkQuery->bind_result($count);
    $checkQuery->fetch();

    if ($count > 0) {
        $checkQuery->close();
        echo '<script>window.location.href="admin_addstudent.php?status=duplicate_lrn";</script>';
        exit();
    }

    $checkQuery->close();

    // Check for duplicate username
    $checkUsernameQuery = $conn->prepare("SELECT COUNT(*) FROM students WHERE username = ?");
    $checkUsernameQuery->bind_param("s", $username);
    
    if (!$checkUsernameQuery->execute()) {
        echo "Error executing check for username: " . $checkUsernameQuery->error;
        exit();
    }

    $checkUsernameQuery->bind_result($usernameCount);
    $checkUsernameQuery->fetch();

    if ($usernameCount > 0) {
        $checkUsernameQuery->close();
        echo '<script>window.location.href="admin_addstudent.php?status=duplicate_username";</script>';
        exit();
    }

    $checkUsernameQuery->close();

    // Prepare and bind an SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO students (name, email, lrn, birthday, grade_level, section, username, password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind the parameters
    $stmt->bind_param("ssssisss", $name, $email, $lrn, $dob, $grade_level, $section, $username, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        // Get the last inserted ID
        $last_id = $stmt->insert_id;
        echo '<script>window.location.href="admin_addstudent.php?status=success&student_id=' . $last_id . '";</script>';
    } else {
        echo '<script>window.location.href="admin_addstudent.php?status=error";</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>


<!-- HTML Form -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Student Information</div>
    </div>
    <div class="card-body">
        <form method="POST" action="add_student.php">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="inputName" class="form-label">Name<span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="inputName" name="name" placeholder="Enter Student Name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputEmail" class="form-label">Email<span class="text-red">*</span></label>
                    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Enter Email" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputLRN" class="form-label">LRN (Learner Reference Number)<span class="text-red">*</span></label>
                    <input type="number" class="form-control" id="inputLRN" name="lrn" placeholder="Enter LRN" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputDOB" class="form-label">Date of Birth<span class="text-red">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-calendar4"></i>
                        </span>
                        <input type="text" class="form-control datepicker-iso-week-numbers" name="dob" placeholder="Select Date of Birth" required>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="inputGradeLevel" class="form-label">Grade Level<span class="text-red">*</span></label>
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
                    <label for="inputSection" class="form-label">Section<span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="inputSection" name="section" placeholder="Enter Section" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputUsername" class="form-label">Username<span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter Username" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputPassword" class="form-label">Password<span class="text-red">*</span></label>
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter Password" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password<span class="text-red">*</span></label>
                    <input type="password" class="form-control" id="inputConfirmPassword" name="confirm_password" placeholder="Confirm Password" required>
                </div>
            </div>
            <div class="form-actions-footer">
                <div class="col-md-12 text-end">
                    <button type="reset" class="btn btn-light">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
