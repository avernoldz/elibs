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
    <title>PSHS eLib Student Returned Books</title>


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

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination a,
        .pagination span {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #007bff;
        }

        .pagination a:hover {
            background-color: #f1f1f1;
        }

        .pagination .active span {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .disabled span {
            color: #6c757d;
            pointer-events: none;
        }

        /* Search container styling */
        .search-container {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* Input group styling */
        .input-group {
            display: flex;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Shadow effect */
            border-radius: 8px;
            /* Rounded corners */
            overflow: hidden;
            /* Ensure elements stay within the rounded container */
        }

        /* Search input styling */
        .search-input {
            padding: 10px 15px;
            border: none;
            outline: none;
            width: 300px;
            font-size: 16px;
            border-radius: 8px 0 0 8px;
        }

        /* Search button styling */
        .search-btn {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Button hover effect */
        .search-btn:hover {
            background-color: #0056b3;
        }
    </style>

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
                        <li>
                            <a href="student_index.php">
                                <i class="bi bi-house"></i>
                                <span class="menu-text">Dashboards</span>
                            </a>
                        </li>
                        <li class="sidebar-dropdown active">
                            <a href="#">
                                <i class="bi bi-bookshelf"></i>
                                <span class="menu-text">Book</span>
                            </a>
                            <div class="sidebar-submenu ">
                                <ul>
                                    <li>
                                        <a href="student_bookcatalog.php">Book Catalog</a>
                                    </li>


                                    <li>
                                        <a href="student_borrow.php">Book Request</a>
                                    </li>
                                    <li>
                                        <a href="student_return.php" class="current-page">Returned Books</a>
                                    </li>
                                </ul>
                        <li>
                            <a href="student_thesiscatalog.php">
                                <i class="bi bi-book"></i>
                                <span class="menu-text">Thesis</span>
                            </a>
                        </li>
                        <li>
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



                    </ul>
                    <!--
                                    <li class="-page-link">
								<a href="student_calendar.php">
									<i class="bi bi-calendar4"></i>
									<span class="menu-text" class="current-page">Calendar</span>
								</a>
							</li>
								</div>
                                </div>
                            
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
                        <a href="student_index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item breadcrumb-active" aria-current="page">Book Request</li>
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
            <!-- Content wrapper scroll start -->
            <div class="content-wrapper-scroll">

                <!-- Content wrapper start -->
                <div class="content-wrapper">

                    <!-- Row start -->
                    <div class="row">
                        <div class="col-sm-12 col-12">



                            <!--	<div class="col-xl-3 col-sm-6 col-12">
								<div class="product-card">
									<img class="product-card-img-top" src="assets/images/food/img5.jpg" alt="Bootstrap Gallery">
									<div class="product-card-body">
										<h5 class="product-title">Grande Duchesse</h5>
										<div class="product-price">
											<span class="disount-price">$25</span>
											<span class="actucal-price">$35</span>
											<span class="off-price">33% Off</span>
										</div>
										<div class="product-rating">
											<div class="rate6 rating-stars"></div>
											<div class="total-ratings">35</div>
										</div>
										<div class="product-description">
											Xuartz movement, manufactured by Zitizen watch co., ltd.
										</div>
										<div class="product-actions">
											<button class="btn btn-success">Add to Cart</button>
										</div>
									</div>
								</div>
							</div>
						</div> -->
                            <!-- Include the PHP code that generates book cards -->
                            <?php include 'student_return.table.php'; ?>



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

    <!-- Data Tables -->
    <script src="assets/vendor/datatables/dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap.min.js"></script>

    <!-- Custom Data tables -->
    <script src="assets/vendor/datatables/custom/custom-datatables.js"></script>

    <!-- Date Range JS -->
    <script src="assets/vendor/daterange/daterange.js"></script>
    <script src="assets/vendor/daterange/custom-daterange.js"></script>

    <!-- Swal -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Main Js Required -->
    <script src="assets/js/main.js"></script>
</body>

</html>