<?php
// Database connection
include 'connection.php';

// Check if the student ID (LRN) is provided
if (isset($_GET['student_id'])) {
    $lrn = intval($_GET['student_id']); // Ensure that student_id is always treated as an integer

    // Fetch student data based on LRN
    $query = "SELECT lrn, name, birthday, email, grade_level, section FROM students WHERE lrn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $lrn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize inputs from form
        $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : $student['name'];
        $birthday = !empty($_POST['birthday']) ? htmlspecialchars(trim($_POST['birthday'])) : $student['birthday'];
        $email = !empty($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : $student['email'];
        $grade_level = !empty($_POST['grade_level']) ? htmlspecialchars(trim($_POST['grade_level'])) : $student['grade_level'];
        $section = !empty($_POST['section']) ? htmlspecialchars(trim($_POST['section'])) : $student['section'];

        // Update student data in the database
        $query = "UPDATE students SET name = ?, birthday = ?, email = ?, grade_level = ?, section = ? WHERE lrn = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $name, $birthday, $email, $grade_level, $section, $lrn);

        if ($stmt->execute()) {
            echo "Student information updated successfully!";
            header("Location: studentlist.php"); // Redirect to the student list
            exit;
        } else {
            echo "Error updating student information.";
        }
    }
} else {
    echo "No student ID provided.";
    exit;
}
?>

<!-- Student Edit Form -->
<form action="edit_student.php?student_id=<?php echo $student['lrn']; ?>" method="POST">
    <div class="form-group">
        <label for="name">Student Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="birthday">Date of Birth</label>
        <input type="date" name="birthday" id="birthday" class="form-control" value="<?php echo htmlspecialchars($student['birthday']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" required>
    </div>
    <div class="form-group">
        <label for="grade_level">Grade Level</label>
        <input type="text" name="grade_level" id="grade_level" class="form-control" value="<?php echo htmlspecialchars($student['grade_level']); ?>" required>
    </div>
    <div class="form-group">
        <label for="section">Section</label>
        <input type="text" name="section" id="section" class="form-control" value="<?php echo htmlspecialchars($student['section']); ?>" required>
    </div>
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="studentlist.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
