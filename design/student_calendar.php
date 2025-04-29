<?php
    include "connection.php"; // Include database connection

    // Check if a session has already started, if not, start it
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Define session timeout duration (e.g., 30 minutes)
    $session_timeout_duration = 1800; // 30 minutes (in seconds)

    // Check if the session has timed out
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout_duration) {
        // If the session has timed out, clear session and redirect to the login page
        session_unset(); // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: student_login.php?timeout=true"); // Redirect with timeout indicator
        exit();
    }

    // Update the last activity time stamp to the current time
    $_SESSION['last_activity'] = time();

    // Regenerate session ID periodically to prevent session fixation attacks
    if (!isset($_SESSION['session_created'])) {
        $_SESSION['session_created'] = time();
    } elseif (time() - $_SESSION['session_created'] > $session_timeout_duration) {
        session_regenerate_id(true); // Regenerate session ID
        $_SESSION['session_created'] = time(); // Reset the session creation time
    }

    // Check if the user is logged in and has the role of 'student'
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['role'] !== 'student') {
        // If the user is not logged in or doesn't have the 'student' role, redirect to login
        header("Location: student_login.php?error=not_logged_in");
        exit();
    }

    // Retrieve the username from the session, default to 'Guest' if not set
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

    // Optional: Additional check to ensure the username is valid
    if ($username === 'Guest') {
        header("Location: student_login.php?error=invalid_user");
        exit();
    }

    // The user is logged in as a valid student, and the session is active at this point

    // (Optional) You can load student-specific data or dashboard components below.
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


		<!-- *************
			************ Vendor Css Files *************
		************ -->

		<!-- Scrollbar CSS -->
		<link rel="stylesheet" href="assets/vendor/overlay-scroll/OverlayScrollbars.min.css">

		<!-- tagsCloud Keywords CSS -->
		<link rel="stylesheet" href="assets/vendor/tagsCloud/tagsCloud.css" />

		<!-- Calendar CSS -->
		<link rel="stylesheet" href="assets/vendor/calendar/css/main.min.css" />
		<link rel="stylesheet" href="assets/vendor/calendar/css/custom.css" />


	</head>

	<body>

		<!-- Loading wrapper start -->
	<!--	<div id="loading-wrapper">
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
							<li class="sidebar-dropdown">
								<a href="#">
									<i class="bi bi-house"></i>
									<span class="menu-text">Dashboards</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="student_index.php" >Analytics</a>
										</li>
										
									</ul>
								</div>
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
										</li>
									</ul>
									<li class="sidebar-dropdown ">
								<a href="#">
									<i class="bi bi-book"></i>
									<span class="menu-text">Thesis</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="student_thesiscatalog.php">Thesis Catalog</a>
										</li>								
										
											
									</ul>
								</div>
									</li>
							<li class="active-page-link">
								<a href="student_calendar.php">
									<i class="bi bi-calendar4"></i>
									<span class="menu-text" class="current-page">Calendar</span>
								</a>
							</li>
				<!-- Sidebar menu ends -->

			</nav>

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
							<a href="student.index.html">Home</a>
						</li>
						<li class="breadcrumb-item">Calendar</li>
						<li class="breadcrumb-item breadcrumb-active" aria-current="page">Daygrid</li>
					</ol>
					<!-- Breadcrumb end -->

					<!-- Header actions ccontainer start -->
					<div class="header-actions-container">

					

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
							
						<li class="dropdown">
							<?php include 'student_user_menu.php'; ?>

								<div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
									<div class="header-profile-actions">
										<a href="profile.html">Profile</a>
										<a href="account-settings.php">Settings</a>
										<a href="logout.php">Logout</a>
									</div>
								</div>
							</li>
						</ul>
						<!-- Header actions end -->

					</div>
					<!-- Header actions ccontainer end -->

				</div>
				<!-- Page header ends -->

				<!-- Content wrapper scroll start -->
				<div class="content-wrapper-scroll">

					<!-- Content wrapper start -->
					<div class="content-wrapper">

						<!-- Row start -->
						<div class="row">
							<div class="col-xxl-12">

								<!-- Card start -->
								<div class="card">
									<div class="card-body">

										<div id="dayGrid"></div>

									</div>
								</div>
								<!-- Card end -->

							</div>
						</div>
						<!-- Row end -->

					</div>
					<!-- Content wrapper end -->

					<!-- App Footer start -->
					<div class="app-footer">
						<span>© Arise admin 2023</span>
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

		<!-- Calendar JS -->
		<script src="assets/vendor/calendar/js/main.min.js"></script>
		<script src="assets/vendor/calendar/custom/daygrid-calendar.js"></script>

		<!-- Main Js Required -->
		<script src="assets/js/main.js"></script>

	</body>

</html>