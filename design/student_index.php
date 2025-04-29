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
	header("Location: login.php?timeout=true"); // Redirect with timeout indicator
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
						<li class="active-page-link">
							<a href="student_index.php">
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
						<!--
							<li class="-page-link">
								<a href="student_calendar.php">
									<i class="bi bi-calendar4"></i>
									<span class="menu-text">Calendar</span>
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
						<a href="student_index.php">Home</a>
					</li>
					<li class="breadcrumb-item breadcrumb-active" aria-current="page">Dashboards</li>
				</ol>
				<!-- Breadcrumb end -->

				<!-- Header actions ccontainer start -->
				<div class="header-actions-container">

					<!-- Search container start -->

					<!-- Search container end -->

					<!-- Leads start -->
					<a href="student_borrow.php" class="leads d-none d-xl-flex">
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
					<?php

					// Check if the student is logged in
					if (!isset($_SESSION['student_id'])) {
						header("Location: login.php");
						exit();
					}

					// Get the student ID from the session
					$student_id = $_SESSION['student_id'];

					// Include the database connection file
					include 'connection.php';

					// Initialize variables to store counts
					$approved_count = 0;
					$book_count = 0;
					$thesis_count = 0;
					$request_count = 0;

					// SQL query to count the number of approved requests for the student
					$sql_approved_books = "SELECT COUNT(*) as total_approved FROM book_requests WHERE student_id = ? AND status = 'Approved'";
					if ($stmt_approved_books = $conn->prepare($sql_approved_books)) {
						$stmt_approved_books->bind_param('i', $student_id);
						$stmt_approved_books->execute();
						$result_approved_books = $stmt_approved_books->get_result();
						if ($result_approved_books) {
							$row_approved_books = $result_approved_books->fetch_assoc();
							$approved_count = $row_approved_books['total_approved'];
						}
						$stmt_approved_books->close();
					}

					// SQL query to count the total number of books available
					$sql_books = "SELECT COUNT(*) as total_books FROM books";
					$result_books = $conn->query($sql_books);
					if ($result_books) {
						$row_books = $result_books->fetch_assoc();
						$book_count = $row_books['total_books'];
					}

					// SQL query to count the total number of theses available
					$sql_theses = "SELECT COUNT(*) as total_theses FROM thesis";
					$result_theses = $conn->query($sql_theses);
					if ($result_theses) {
						$row_theses = $result_theses->fetch_assoc();
						$thesis_count = $row_theses['total_theses'];
					}

					// SQL query to count the total number of book requests made by the student
					$sql_requests = "SELECT COUNT(*) as total_requests FROM book_requests WHERE student_id = ?";
					if ($stmt_requests = $conn->prepare($sql_requests)) {
						$stmt_requests->bind_param('i', $student_id);
						$stmt_requests->execute();
						$result_requests = $stmt_requests->get_result();
						if ($result_requests) {
							$row_requests = $result_requests->fetch_assoc();
							$request_count = $row_requests['total_requests'];
						}
						$stmt_requests->close();
					}

					// Close the database connection
					$conn->close();
					?>

					<!-- Display Stats in Dashboard -->
					<div class="row">
						<!-- Approved Requests Section -->
						<div class="col-xxl-3 col-sm-6 col-12">
							<a href="student_borrow.php" style="text-decoration: none; color: inherit;"> <!-- Link to the student list page -->
								<div class="stats-tile">
									<div class="sale-icon shade-green">
										<i class="bi-journal-arrow-down"></i>
									</div>
									<div class="sale-details">
										<h3 class="text-green"><?php echo $approved_count; ?></h3>
										<p>Approved Requests</p>
									</div>
								</div>
							</a>
						</div>

						<!-- Total Book Count Section -->
						<div class="col-xxl-3 col-sm-6 col-12">
							<a href="student_bookcatalog.php" style="text-decoration: none; color: inherit;"> <!-- Link to the student list page -->
								<div class="stats-tile">
									<div class="sale-icon shade-blue">
										<i class="bi bi-bookshelf"></i>
									</div>
									<div class="sale-details">
										<h3 class="text-blue"><?php echo $book_count; ?></h3>
										<p>Total Books</p>
									</div>
								</div>
							</a>
						</div>

						<!-- Thesis Count Section -->
						<div class="col-xxl-3 col-sm-6 col-12">
							<a href="student_thesiscatalog.php" style="text-decoration: none; color: inherit;"> <!-- Link to the student list page -->
								<div class="stats-tile">
									<div class="sale-icon shade-yellow">
										<i class="bi bi-book"></i>
									</div>
									<div class="sale-details">
										<h3 class="text-yellow"><?php echo $thesis_count; ?></h3>
										<p>Total Theses</p>
									</div>
								</div>
							</a>
						</div>

						<!-- Total Requests Count Section -->
						<div class="col-xxl-3 col-sm-6 col-12">
							<a href="student_borrow.php" style="text-decoration: none; color: inherit;"> <!-- Link to the student list page -->
								<div class="stats-tile">
									<div class="sale-icon shade-red">
										<i class="bi-envelope"></i>
									</div>
									<div class="sale-details">
										<h3 class="text-red"><?php echo $request_count; ?></h3>
										<p>Total Requests</p>
									</div>
								</div>
							</a>
						</div>
					</div>

					<!-- Row end -->
					<div class="col-xxl-6 col-sm-12 col-12">

					</div>
					<div class="col-xxl-6 col-sm-12 col-12">
						<div class="card">
							<div class="card-body">
								<!-- Row start -->
								<?php
								// Include your connection file
								include 'connection.php';

								// Ensure session is started
								if (session_status() == PHP_SESSION_NONE) {
									session_start();
								}

								// Assume the student ID is available
								$student_id = $_SESSION['student_id'] ?? null;

								// Function to fetch book request counts by status
								function getBookRequestCount($conn, $student_id, $status)
								{
									// SQL query to count requests for a specific status
									$sql = "SELECT COUNT(*) as count FROM book_requests WHERE status = ? AND student_id = ?";
									$stmt = $conn->prepare($sql);
									$stmt->bind_param("si", $status, $student_id);
									$stmt->execute();
									$result = $stmt->get_result();
									$count = ($result && $row = $result->fetch_assoc()) ? $row['count'] : 0;
									$stmt->close();
									return $count;
								}

								// Function to display book request statistics for the student
								function displayStudentBookRequestStats($conn, $student_id)
								{
									if (!$student_id) {
										return "<p>Error: Student ID not provided.</p>";
									}

									// Fetch counts
									$pending_requests = getBookRequestCount($conn, $student_id, 'Pending');
									$approved_requests = getBookRequestCount($conn, $student_id, 'Approved');
									$rejected_requests = getBookRequestCount($conn, $student_id, 'Rejected');

									// Return HTML content populated for a student view
									return "
        <div class='row'>
            <div class='col-sm-6 col-12'>
                <div class='stats-tile2-container'>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-hourglass-split text-blue'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Pending Requests</h5>
                            <p class='growth'>{$pending_requests} Pending</p>
                        </div>
                    </div>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-check-circle text-green'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Approved Requests</h5>
                            <p class='growth'>{$approved_requests} Approved</p>
                        </div>
                    </div>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-x-circle text-red'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Rejected Requests</h5>
                            <p class='growth'>{$rejected_requests} Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-sm-6 col-12'>
                <div id='graph8'></div>
            </div>
        </div>";
								}

								// Usage of the function
								if ($student_id) {
									echo displayStudentBookRequestStats($conn, $student_id);
								} else {
									echo "<p>Error: Unable to retrieve student data.</p>";
								}

								$conn->close();
								?>


								<!-- Row end -->
							</div>
						</div>
					</div>



					<?php
					include 'connection.php';  // Include the database connection

					// Check if the connection was successful
					if (!$conn) {
						die("Database connection failed: " . mysqli_connect_error());
					}

					// SQL query to get the top 3 most requested books
					$sql = "
    SELECT 
        books.title, 
        books.author, 
        books.book_id, 
        books.book_image_path,  
        COUNT(book_requests.book_id) AS request_count
    FROM 
        book_requests
    JOIN 
        books ON book_requests.book_id = books.book_id
    GROUP BY 
        book_requests.book_id
    ORDER BY 
        request_count DESC
    LIMIT 3";  // Limit to top 3 most requested books

					$result = $conn->query($sql);
					?>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Top 3 Requested Books</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table v-middle">
											<thead>
												<tr>
													<th>Book Cover</th>
													<th>Title</th>
													<th>Author</th>
													<th>Requests</th>
												</tr>
											</thead>
											<tbody>
												<?php
												if ($result && $result->num_rows > 0) {
													while ($row = $result->fetch_assoc()) {
														echo "<tr>";
														echo "<td><div class='media-box'>";
														echo "<img src='" . htmlspecialchars($row['book_image_path'], ENT_QUOTES, 'UTF-8') . "' class='media-avatar' alt='Cover of " . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "'>";
														echo "</div></td>";
														echo "<td>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</td>";
														echo "<td>" . htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8') . "</td>";
														echo "<td>" . (int)$row['request_count'] . "</td>";
														echo "</tr>";
													}
												} else {
													echo "<tr><td colspan='4'>No records found</td></tr>";
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php
					$conn->close();
					?>

					<!-- Row start -->
					<div class="row">
							<div class="col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Orders</div>
									</div>
									<div class="card-body">

										<div class="table-responsive">
											<table class="table v-middle">
												<thead>
													<tr>
														<th>Customer</th>
														<th>Product</th>
														<th>User ID</th>
														<th>Ordered Placed</th>
														<th>Amount</th>
														<th>Payment Status</th>
														<th>Order Status</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<div class="media-box">
																<img src="assets/images/user3.png" class="media-avatar" alt="Bootstrap Gallery">
																<div class="media-box-body">
																	<div class="text-truncate">Ellie Collins</div>
																</div>
															</div>
														</td>
														<td>
															<div class="media-box">
																<img src="assets/images/food/img3.jpg" class="media-avatar" alt="Admin Themes">
																<div class="media-box-body">
																	<div class="text-truncate">Ginger Snacks</div>
																</div>
															</div>
														</td>
														<td>Arise827</td>
														<td>12/12/2021</td>
														<td>$18.00</td>
														<td>
															<span class="text-green td-status"><i class="bi bi-check-circle"></i> Paid</span>
														</td>
														<td>
															<span class="badge shade-green min-90">Delivered</span>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media-box">
																<img src="assets/images/user.png" class="media-avatar" alt="Bootstrap Gallery">
																<div class="media-box-body">
																	<div class="text-truncate">Sophie Nguyen</div>
																</div>
															</div>
														</td>
														<td>
															<div class="media-box">
																<img src="assets/images/food/img6.jpg" class="media-avatar" alt="Admin Themes">
																<div class="media-box-body">
																	<div class="text-truncate">Guava Sorbet</div>
																</div>
															</div>
														</td>
														<td>Arise253</td>
														<td>18/12/2021</td>
														<td>$32.00</td>
														<td>
															<span class="text-red td-status"><i class="bi bi-x-circle"></i> Failed</span>
														</td>
														<td>
															<span class="badge shade-red min-90">Cancelled</span>
														</td>
													</tr>
													<tr>
														<td>
															<div class="media-box">
																<img src="assets/images/user4.png" class="media-avatar" alt="Bootstrap Gallery">
																<div class="media-box-body">
																	<div class="text-truncate">Darcy Ryan</div>
																</div>
															</div>
														</td>
														<td>
															<div class="media-box">
																<img src="assets/images/food/img5.jpg" class="media-avatar" alt="Admin Themes">
																<div class="media-box-body">
																	<div class="text-truncate">Gooseberry Surprise</div>
																</div>
															</div>
														</td>
														<td>Arise878</td>
														<td>22/12/2021</td>
														<td>$19.00</td>
														<td>
															<span class="text-blue td-status"><i class="bi bi-clock-history"></i> Awaiting</span>
														</td>
														<td>
															<span class="badge shade-blue min-90">Processing</span>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

									</div>
								</div>
							</div>
						</div>
						<!-- Row end -->

						<!-- Row start -->
						<div class="row">
							<div class="col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Transactions</div>
									</div>
									<div class="card-body">
										<div class="scroll370">
											<div class="transactions-container">
												<div class="transaction-block">
													<div class="transaction-icon shade-blue">
														<i class="bi bi-credit-card"></i>
													</div>
													<div class="transaction-details">
														<h4>Visa Card</h4>
														<p class="text-truncate">Laptop Ordered</p>
													</div>
													<div class="transaction-amount text-blue">$1590</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-green">
														<i class="bi bi-paypal"></i>
													</div>
													<div class="transaction-details">
														<h4>Paypal</h4>
														<p class="text-truncate">Payment Received</p>
													</div>
													<div class="transaction-amount text-green">$310</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-blue">
														<i class="bi bi-pin-map"></i>
													</div>
													<div class="transaction-details">
														<h4>Travel</h4>
														<p class="text-truncate">Yosemite Trip</p>
													</div>
													<div class="transaction-amount text-blue">$4900</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-blue">
														<i class="bi bi-bag-check"></i>
													</div>
													<div class="transaction-details">
														<h4>Shopping</h4>
														<p class="text-truncate">Bill Paid</p>
													</div>
													<div class="transaction-amount text-blue">$285</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-green">
														<i class="bi bi-boxes"></i>
													</div>
													<div class="transaction-details">
														<h4>Bank</h4>
														<p class="text-truncate">Investment</p>
													</div>
													<div class="transaction-amount text-green">$150</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-green">
														<i class="bi bi-paypal"></i>
													</div>
													<div class="transaction-details">
														<h4>Paypal</h4>
														<p class="text-truncate">Amount Received</p>
													</div>
													<div class="transaction-amount text-green">$790</div>
												</div>
												<div class="transaction-block">
													<div class="transaction-icon shade-blue">
														<i class="bi bi-credit-card-2-front"></i>
													</div>
													<div class="transaction-details">
														<h4>Credit Card</h4>
														<p class="text-truncate">Online Shopping</p>
													</div>
													<div class="transaction-amount text-red">$280</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Tasks</div>
									</div>
									<div class="card-body">
										<div id="taskGraph"></div>
										<ul class="task-list-container">
											<li class="task-list-item">
												<div class="task-icon shade-blue">
													<i class="bi bi-clipboard-plus"></i>
												</div>
												<div class="task-info">
													<h5 class="task-title">New</h5>
													<p class="amount-spend">12</p>
												</div>
											</li>
											<li class="task-list-item">
												<div class="task-icon shade-green">
													<i class="bi bi-clipboard-check"></i>
												</div>
												<div class="task-info">
													<h5 class="task-title">Done</h5>
													<p class="amount-spend">15</p>
												</div>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Notifications</div>
									</div>
									<div class="card-body">
										<div class="scroll370">
											<ul class="user-messages">
												<li>
													<div class="customer shade-blue">MK</div>
													<div class="delivery-details">
														<span class="badge shade-blue">Sales</span>
														<h5>Marie Kieffer</h5>
														<p>Thanks for choosing Apple product, further if you have any questions please contact sales
															team.</p>
													</div>
												</li>
												<li>
													<div class="customer shade-blue">ES</div>
													<div class="delivery-details">
														<span class="badge shade-blue">Marketing</span>
														<h5>Ewelina Sikora</h5>
														<p>Boost your sales by 50% with the easiest and proven marketing tool for customer enggement
															&amp; motivation.</p>
													</div>
												</li>
												<li>
													<div class="customer shade-blue">TN</div>
													<div class="delivery-details">
														<span class="badge shade-blue">Business</span>
														<h5>Teboho Ncube</h5>
														<p>Use an exclusive promo code HKYMM50 and get 50% off on your first order in the new year.
														</p>
													</div>
												</li>
												<li>
													<div class="customer shade-blue">CJ</div>
													<div class="delivery-details">
														<span class="badge shade-blue">Admin</span>
														<h5>Carla Jackson</h5>
														<p>Befor inviting the administrator, you must create a role that can be assigned to them.
														</p>
													</div>
												</li>
												<li>
													<div class="customer shade-red">JK</div>
													<div class="delivery-details">
														<span class="badge shade-red">Security</span>
														<h5>Julie Kemp</h5>
														<p>Your security subscription has expired. Please renew the subscription.</p>
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Activity</div>
									</div>
									<div class="card-body">

										<div class="scroll370">
											<div class="activity-container">
												<div class="activity-block">
													<div class="activity-user">
														<img src="assets/images/user.png" alt="Activity User">
													</div>
													<div class="activity-details">
														<h4>Lilly Desmet</h4>
														<h5>3 hours ago</h5>
														<p>Sent invoice ref. #23457</p>
														<span class="badge shade-green">Sent</span>
													</div>
												</div>
												<div class="activity-block">
													<div class="activity-user">
														<img src="assets/images/user3.png" alt="Activity User">
													</div>
													<div class="activity-details">
														<h4>Jennifer Wilson</h4>
														<h5>7 hours ago</h5>
														<p>Paid invoice ref. #23459</p>
														<span class="badge shade-red">Payments</span>
													</div>
												</div>
												<div class="activity-block">
													<div class="activity-user">
														<img src="assets/images/user4.png" alt="Activity User">
													</div>
													<div class="activity-details">
														<h4>Elliott Hermans</h4>
														<h5>1 day ago</h5>
														<p>Paid invoice ref. #23473</p>
														<span class="badge shade-green">Paid</span>
													</div>
												</div>
												<div class="activity-block">
													<div class="activity-user">
														<img src="assets/images/user5.png" alt="Activity User">
													</div>
													<div class="activity-details">
														<h4>Sophie Michiels</h4>
														<h5>3 day ago</h5>
														<p>Paid invoice ref. #26788</p>
														<span class="badge shade-green">Sent</span>
													</div>
												</div>
												<div class="activity-block">
													<div class="activity-user">
														<img src="assets/images/user2.png" alt="Activity User">
													</div>
													<div class="activity-details">
														<h4>Ilyana Maes</h4>
														<h5>One week ago</h5>
														<p>Paid invoice ref. #34546</p>
														<span class="badge shade-red">Invoice</span>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
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

	<!-- Apex Charts -->
	<script src="assets/vendor/apex/apexcharts.min.js"></script>
	<script src="assets/vendor/apex/custom/sales/salesGraph.js"></script>
	<script src="assets/vendor/apex/custom/sales/revenueGraph.js"></script>
	<script src="assets/vendor/apex/custom/sales/taskGraph.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/byChannel.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/byCountry.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/byDevice.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/orders.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/results.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/visitors.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/demography.js"></script>
	<script src="assets/vendor/apex/custom/repotrs/deals.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph1.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph2.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph3.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph4.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph5.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph6.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph7.js"></script>
	<script src="assets/vendor/apex/custom/widgets/graph8.js"></script>

	<!-- jVector Maps -->
	<script src="assets/vendor/jvectormap/jquery-jvectormap-2.0.5.min.js"></script>
	<script src="assets/vendor/jvectormap/gdp-data.js"></script>
	<script src="assets/vendor/jvectormap/world-mill-en.js"></script>
	<script src="assets/vendor/jvectormap/africa-mill.js"></script>
	<script src="assets/vendor/jvectormap/europe-mill.js"></script>
	<script src="assets/vendor/jvectormap/custom/map-europe.js"></script>
	<script src="assets/vendor/jvectormap/custom/map-africa.js"></script>
	<script src="assets/vendor/jvectormap/custom/world-map-markers2.js"></script>

	<!-- jQcloud Keywords -->
	<script src="assets/vendor/tagsCloud/tagsCloud.js"></script>

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

</body>

</html>