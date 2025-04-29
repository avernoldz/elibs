<?php
// Include the connection to the database
include 'connection.php';

// Start session
session_start();
if (!isset($_SESSION['student_id'])) {
    die("Error: Student ID not found in session. Please log in again.");
}

$studentID = $_SESSION['student_id']; // Get student ID from session

// Fetch student data
$query = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: No student data found for the provided student ID.");
}

$studentData = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize an empty array for dynamic updates
    $updates = [];
    $types = '';
    $params = [];

    // Check and validate each field
    if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
        $updates[] = "name = ?";
        $types .= 's';
        $params[] = trim($_POST['name']);
    }

    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $updates[] = "email = ?";
        $types .= 's';
        $params[] = trim($_POST['email']);
    }

    if (isset($_POST['username']) && !empty(trim($_POST['username']))) {
        $updates[] = "username = ?";
        $types .= 's';
        $params[] = trim($_POST['username']);
    }

    if (isset($_POST['lrn']) && !empty(trim($_POST['lrn']))) {
        $updates[] = "lrn = ?";
        $types .= 's';
        $params[] = trim($_POST['lrn']);
    }

    if (isset($_POST['grade_level']) && !empty(trim($_POST['grade_level']))) {
        $updates[] = "grade_level = ?";
        $types .= 's';
        $params[] = trim($_POST['grade_level']);
    }

    if (isset($_POST['section']) && !empty(trim($_POST['section']))) {
        $updates[] = "section = ?";
        $types .= 's';
        $params[] = trim($_POST['section']);
    }

    if (isset($_POST['birthday']) && !empty($_POST['birthday'])) {
        $updates[] = "birthday = ?";
        $types .= 's';
        $params[] = $_POST['birthday'];
    }

    // Handle password only if provided
    if (isset($_POST['enterPassword']) && !empty($_POST['enterPassword'])) {
        $updates[] = "password = ?";
        $types .= 's';
        $params[] = password_hash($_POST['enterPassword'], PASSWORD_DEFAULT); // Hash the password
    }

    // Proceed with the update if there are fields to update
    if (!empty($updates)) {
        $query = "UPDATE students SET " . implode(", ", $updates) . " WHERE student_id = ?";
        $types .= 'i';
        $params[] = $studentID;

        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $success = "Account settings updated successfully.";
        } else {
            $error = "Error updating account settings: " . $conn->error;
        }
    } else {
        $error = "No fields were provided to update.";
    }
}
?>


<!doctype html>
<html lang="en">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

		<!-- Title -->
		<title>PSHS eLib Student Dashboards</title>


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



		<!-- *************
			************ Vendor Css Files *************
		************ -->

		<!-- Scrollbar CSS -->
		<link rel="stylesheet" href="assets/vendor/overlay-scroll/OverlayScrollbars.min.css">

		<!-- tagsCloud Keywords CSS -->
		<link rel="stylesheet" href="assets/vendor/tagsCloud/tagsCloud.css" />

	</head>

	<body>

		<!-- Loading wrapper start -->
		<div id="loading-wrapper">
			<div class="spinner">
				<div class="line1"></div>
				<div class="line2"></div>
				<div class="line3"></div>
				<div class="line4"></div>
				<div class="line5"></div>
				<div class="line6"></div>
			</div>
		</div>
		<!-- Loading wrapper end -->

		<!-- Page wrapper start -->
		<div class="page-wrapper">

			<!-- Sidebar wrapper start -->
        
			<nav class="sidebar-wrapper">

				<!-- Sidebar brand starts -->
				<div class="sidebar-brand">
					<a href="student_index.php" class="logo">
						<img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Admin Dashboards" />
					</a>
				</div>
				<!-- Sidebar brand starts -->

				<!-- Sidebar menu starts -->
				<div class="sidebar-menu">
					<div class="sidebarMenuScroll">
						<ul>
						<li  >
									<a  href="student_index.php">
										<i class="bi bi-house"></i>
										<span class="menu-text">Dashboards</span>
									</a>
								</li>
							
							
							<li class="sidebar-dropdown ">
								<a href="#">
									<i class="bi bi-bookshelf"></i>
									<span class="menu-text">Book</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="student_bookcatalog.php">Book Catalog</a>
										</li>
									
										<li>
											<a href="student_borrow.php">Book Request</a>
										</li>
										<li>
											<a href="student_return.php">Returned Books</a>
										
									</ul>
									
									<li>
									<a  href="student_thesiscatalog.php">
										<i class="bi bi-book"></i>
										<span class="menu-text">Thesis</span>
									</a>
								</li>
									<li class="active-page-link">
									<a href="student_account_setting.php">
										<i class="bi bi-gear"></i>
										<span class="menu-text">Account Settings</span>
									</a>
								</li>
								<li>
									<a href="logout.php">
										<i class="bi bi-box-arrow-right"></i>
										<span class="menu-text">Log Out</span>
									</a>
								</li>
				<!-- Sidebar menu ends -->

			</nav>
			<!-- Sidebar wrapper end -->

			<!-- *************
				************ Main container start *************
			************* -->
			<div class="main-container">

				<!-- Page header starts -->
				<div class="page-header">

					<div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>

					<!-- Breadcrumb start -->
					<ol class="breadcrumb d-md-flex d-none">
						<li class="breadcrumb-item">
							<i class="bi bi-house"></i>
							<a href="admin_index.php">Home</a>
						</li>
						<li class="breadcrumb-item breadcrumb-active" aria-current="page">Account Settings</li>
					</ol>
					<!-- Breadcrumb end -->

					<!-- Header actions ccontainer start -->
					<div class="header-actions-container">

						<!-- Search container start -->
						
						<!-- Search container end -->

						<!-- Leads start -->
						<a href="orders.html" class="leads d-none d-xl-flex">
							<div class="lead-details">You have <span class="count"> 21 </span> new leads </div>
							<span class="lead-icon"><i
									class="bi bi-bell-fill animate__animated animate__swing animate__infinite infinite"></i><b
									class="dot animate__animated animate__heartBeat animate__infinite"></b></span>
						</a>
						<!-- Leads end -->

						<!-- Header actions start -->
						<ul class="header-actions">
							<!--<li class="dropdown d-none d-md-block">
								<a href="#" id="countries" data-toggle="dropdown" aria-haspopup="true">
									<img src="assets/images/flags/1x1/br.svg" class="flag-img" alt="Admin Panels" />
								</a>
								<div class="dropdown-menu dropdown-menu-end mini" aria-labelledby="countries">
									<div class="country-container">
										<a href="index.html">
											<img src="assets/images/flags/1x1/us.svg" alt="Clean Admin Dashboards" />
										</a>
										<a href="index.html">
											<img src="assets/images/flags/1x1/in.svg" alt="Google Dashboards" />
										</a>
										<a href="index.html">
											<img src="assets/images/flags/1x1/gb.svg" alt="AI Admin Dashboards" />
										</a>
										<a href="index.html">
											<img src="assets/images/flags/1x1/tr.svg" alt="Modern Dashboards" />
										</a>
										<a href="index.html">
											<img src="assets/images/flags/1x1/ca.svg" alt="Best Admin Dashboards" />
										</a>
									</div>
								</div>
							</li>-->
							<li class="dropdown">
							<?php include 'student_user_menu.php'; ?>

								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
									<div class="header-profile-actions">
										<a href="profile.html">Profile</a>
										<a href="student_account_setting.php">Settings</a>
										<a href="logout.php">Logout</a>
									</div>
								</div>
							</li>
						</ul>
						<!-- Header actions end -->

					</div>
					<!-- Header actions container end -->

				</div>
				<!-- Page header ends -->
				<!-- Page header ends -->

				<!-- Content wrapper scroll start -->
				<div class="content-wrapper-scroll">

					<!-- Content wrapper start -->
					<div class="content-wrapper">

						<!-- Row start -->
						<div class="row">
							<div class="col-xl-12">
								<!-- Card start -->
								<div class="card">
									<div class="card-body">

									<div class="container">
									<div class="card-title">Account Setting</div>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="student_account_setting.php" method="POST">
    <div class="row">
        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($studentData['name']); ?>" readonly>
            </div>
        </div>

        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($studentData['email']); ?>" placeholder="student@example.com">
            </div>
        </div>

        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($studentData['username']); ?>" placeholder="Username">
            </div>
        </div>
		<div class="col-xxl-4 col-sm-6 col-12">
    <div class="mb-3">
        <label class="form-label">Birthday</label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-calendar4"></i>
            </span>
            <input type="text" name="birthday" 
                class="form-control datepicker" 
                value="<?php echo htmlspecialchars($studentData['birthday']); ?>" 
                required>
        </div>
    </div>
</div>

       

        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" 
                       value="<?php echo htmlspecialchars($studentData['section']); ?>" placeholder="Section">
            </div>
        </div>

		<div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="grade_level" class="form-label">Grade Level</label>
                <input type="text" class="form-control" id="grade_level" name="grade_level" 
                       value="<?php echo htmlspecialchars($studentData['grade_level']); ?>" placeholder="Grade Level">
            </div>
        </div>


        <!-- LRN (Read-Only) -->
        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="lrn" class="form-label">Learning Resource (LRN) Number</label>
                <input type="text" class="form-control" id="lrn" name="lrn" 
                       value="<?php echo htmlspecialchars($studentData['lrn']); ?>" readonly>
            </div>
        </div>

        <div class="col-xxl-4 col-sm-6 col-12">
            <div class="mb-3">
                <label for="enterPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="enterPassword" name="enterPassword" 
                       placeholder="Enter New Password">
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-12">
        <hr>
        <button class="btn btn-info" type="submit">Save Settings</button>
    </div>
</form>

</div>
</div>
</div>
							</div>
						</div>
						



					</div>
					<!-- Content wrapper end -->

					<!-- App Footer start -->
					<div class="app-footer">
						<span>© PSHS 2024</span>
					</div>
					<!-- App footer end -->

				</div>
				<!-- Content wrapper scroll end -->

			</div>
			<!-- *************
				************ Main container end *************
			************* -->

		</div>
		<!-- Page wrapper end -->

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

		<!-- Date Range JS -->
		<script src="assets/vendor/daterange/daterange.js"></script>
		<script src="assets/vendor/daterange/custom-daterange.js"></script>

		<!-- Dropzone JS -->
		<script src="assets/vendor/dropzone/dropzone.min.js"></script>

		<!-- Main Js Required -->
		<script src="assets/js/main.js"></script>

	</body>

</html>