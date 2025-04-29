
<?php
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['role'] !== 'admin') {
    // If the user is not logged in or doesn't have the 'admin' role, redirect to login page
    header("Location: login.php");
    exit();
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
		<title>PSHS eLib Admin Dashboards</title>


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
					<a href="admin_index.php" class="logo">
						<img src="assets/images/PSHS_LOGO-removebg-preview.png" alt="Admin Dashboards" />
					</a>
				</div>
				<!-- Sidebar brand starts -->

				<!-- Sidebar menu starts -->
				<div class="sidebar-menu">
					<div class="sidebarMenuScroll">
						<ul>
						<li  class="active-page-link">
									<a  href="admin_index.php">
										<i class="bi bi-house"></i>
										<span class="menu-text">Dashboards</span>
									</a>
								</li>
							
							<li class="sidebar-dropdown">
								<a href="#">
									<i class="bi bi-people"></i>
									<span class="menu-text">User Management</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="admin_studentlist.php">Student List</a>
										</li>
										<li>
											<a href="admin_achivestudent.php" >Archive Accounts</a>
										</li>
										<li>
											<a href="admin_addstudent.php">Add Student</a>
										</li>
										
										
										<li>
											<a href="admin_reviews.php">Reviews</a>
										</li>
									</ul>
								</div>
							</li>
							<li class="sidebar-dropdown ">
								<a href="#">
									<i class="bi bi-bookshelf"></i>
									<span class="menu-text">Book Management</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="admin_bookcatalog.php">Book Catalog</a>
										</li>
										<li>
											<a href="admin_addbooks.php">Add Books</a>
										</li>
										<li>
											<a href="admin_requests.php">View Book Requests</a>
										</li>
										<li>
											<a href="admin_returned_books.php">Returned Books</a>
										</li>
										
									</ul>
									<li class="sidebar-dropdown ">
								<a href="#">
									<i class="bi bi-book"></i>
									<span class="menu-text">Thesis Management</span>
								</a>
								<div class="sidebar-submenu">
									<ul>
										<li>
											<a href="admin_thesiscatalog.php">Thesis Catalog</a>
										</li>
										<li>
											<a href="admin_addthesis.php">Add Thesis</a>
										</li>
										
									</ul>
								</div>
								<li>
									<a href="account-settings.php">
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
						<li class="breadcrumb-item breadcrumb-active" aria-current="page">Dashboards</li>
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
							<?php include 'admin_user.php'; ?>

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
					<!-- Header actions container end -->

				</div>
				<!-- Page header ends -->

				<!-- Content wrapper scroll start -->
				<div class="content-wrapper-scroll">

					<!-- Content wrapper start -->
					<div class="content-wrapper">

						<!-- Row start -->
<?php
// Include connection file
include 'connection.php';

// SQL query to count the number of students
$sql_students = "SELECT COUNT(*) as total_students FROM students";
$result_students = $conn->query($sql_students);

if ($result_students) {
    $row_students = $result_students->fetch_assoc();
    $student_count = $row_students['total_students'];
} else {
    $student_count = 0;  // Default to 0 if query fails
}

// SQL query to count the number of books
$sql_books = "SELECT COUNT(*) as total_books FROM books";  // Change 'books' to your actual table
$result_books = $conn->query($sql_books);

if ($result_books) {
    $row_books = $result_books->fetch_assoc();
    $book_count = $row_books['total_books'];
} else {
    $book_count = 0;  // Default to 0 if query fails
}

// SQL query to count the number of theses
$sql_theses = "SELECT COUNT(*) as total_theses FROM thesis";  // Change 'thesis' to your actual table
$result_theses = $conn->query($sql_theses);

if ($result_theses) {
    $row_theses = $result_theses->fetch_assoc();
    $thesis_count = $row_theses['total_theses'];
} else {
    $thesis_count = 0;  // Default to 0 if query fails
}

// SQL query to count the number of borrowed books
$sql_borrowed = "SELECT COUNT(*) as total_borrowed FROM book_requests";  // Change 'borrowed_books' to your actual table
$result_borrowed = $conn->query($sql_borrowed);

if ($result_borrowed) {
    $row_borrowed = $result_borrowed->fetch_assoc();
    $borrowed_count = $row_borrowed['total_borrowed'];
} else {
    $borrowed_count = 0;  // Default to 0 if query fails
}

// Close the database connection
$conn->close();
?>

<div class="row">
    <!-- Student Count Section -->
<div class="col-xxl-3 col-sm-6 col-12">
    <a href="admin_studentlist.php" style="text-decoration: none; color: inherit;"> <!-- Link to the student list page -->
        <div class="stats-tile">
            <div class="sale-icon shade-red">
                <i class="bi bi-people"></i>
            </div>
            <div class="sale-details">
                <h3 class="text-red"><?php echo $student_count; ?></h3>
                <p>Students</p>
            </div>
        </div>
    </a>
</div>


    <!-- Book Count Section -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <a href="admin_bookcatalog.php" style="text-decoration: none; color: inherit;"> <!-- Link to the book list page -->
            <div class="stats-tile">
                <div class="sale-icon shade-blue">
                    <i class="bi bi-bookshelf"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-blue"><?php echo $book_count; ?></h3>
                    <p>Books</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Thesis Count Section -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <a href="admin_thesiscatalog.php" style="text-decoration: none; color: inherit;"> <!-- Link to the thesis list page -->
            <div class="stats-tile">
                <div class="sale-icon shade-yellow">
                    <i class="bi bi-book"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-yellow"><?php echo $thesis_count; ?></h3>
                    <p>Thesis</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Borrowed Count Section -->
    <div class="col-xxl-3 col-sm-6 col-12">
        <a href="admin_requests.php" style="text-decoration: none; color: inherit;"> <!-- Link to the borrowed books page -->
            <div class="stats-tile">
                <div class="sale-icon shade-green">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="sale-details">
                    <h3 class="text-green"><?php echo $borrowed_count; ?></h3>
                    <p>Request</p>
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

// Function to fetch and display book request statistics
function displayBookRequestStats($conn) {
    // Query to count pending book requests
    $sql_pending_requests = "SELECT COUNT(*) as pending_requests FROM book_requests WHERE status = 'Pending'";
    $result_pending_requests = $conn->query($sql_pending_requests);
    $pending_requests = ($result_pending_requests) ? $result_pending_requests->fetch_assoc()['pending_requests'] : 0;

    // Query to count approved book requests
    $sql_approved_requests = "SELECT COUNT(*) as approved_requests FROM book_requests WHERE status = 'Approved'";
    $result_approved_requests = $conn->query($sql_approved_requests);
    $approved_requests = ($result_approved_requests) ? $result_approved_requests->fetch_assoc()['approved_requests'] : 0;

    // Query to count rejected book requests
    $sql_rejected_requests = "SELECT COUNT(*) as rejected_requests FROM book_requests WHERE status = 'Rejected'";
    $result_rejected_requests = $conn->query($sql_rejected_requests);
    $rejected_requests = ($result_rejected_requests) ? $result_rejected_requests->fetch_assoc()['rejected_requests'] : 0;

    // Return the HTML content dynamically populated with the data
    return "
        <div class='row'>
            <div class='col-sm-6 col-12'>
                <div class='stats-tile2-container'>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-hourglass-split text-blue'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Pending Book Requests</h5>
                            <p class='growth'>Pending {$pending_requests}</p>
                        </div>
                    </div>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-check-circle text-green'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Approved Book Requests</h5>
                            <p class='growth'>Approved {$approved_requests}</p>
                        </div>
                    </div>
                    <div class='stats-tile2'>
                        <div class='sale-icon'>
                            <i class='bi bi-x-circle text-red'></i>
                        </div>
                        <div class='sale-details'>
                            <h5>Rejected Book Requests</h5>
                            <p class='growth'>Rejected {$rejected_requests}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-sm-6 col-12'>
                <div id='graph8'></div>
            </div>
        </div>";
}

// Usage of the function in your page
echo displayBookRequestStats($conn);

$conn->close();
?>

										<!-- Row end -->
									</div>
								</div>
							</div>

						<!-- Row start -->
						<div class="row">
							<div class="col-xxl-4 col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Visitors</div>
									</div>
									<div class="card-body">

										<div id="visitors" class="chart-height-xl"></div>

									</div>
								</div>
							</div>
							<div class="col-xxl-4 col-sm-6 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Demography</div>
									</div>
									<div class="card-body">

										<div id="demography" class="chart-height-xl auto-align-graph"></div>

									</div>
								</div>
							</div>
							<div class="col-xxl-4 col-sm-12 col-12">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Results</div>
									</div>
									<div class="card-body">

										<div id="results" class="chart-height-xl"></div>

									</div>
								</div>
							</div>
						</div>
						<!-- Row end -->

						<?php
								include 'connection.php';  // Reuse the existing database connection

								// SQL query to get the most requested books with book details and image path
								$sql = "
								SELECT 
									books.title, 
									books.author, 
									books.book_id, 
									books.book_image_path,  -- Select the image path
									COUNT(book_requests.book_id) AS request_count
								FROM 
									book_requests
								JOIN 
									books ON book_requests.book_id = books.book_id
								GROUP BY 
									book_requests.book_id
								ORDER BY 
									request_count DESC
								LIMIT 10";  // Limit to top 10 most requested books

								$result = $conn->query($sql);
								?>

								<div class="row">
									<div class="col-12">
										<div class="card">
											<div class="card-header">
												<div class="card-title">Most Requested Books</div>
											</div>
											<div class="card-body">
												<div class="table-responsive">
													<table class="table v-middle">
														<thead>
															<tr>
																<th>Book Cover</th>
																<th>Title</th>
																<th>Author</th>
																<th>Book ID</th>
																<th>Requests</th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($result->num_rows > 0) {
																while ($row = $result->fetch_assoc()) {
																	echo "<tr>";
																	echo "<td><div class='media-box'>";
																	echo "<img src='" . $row['book_image_path'] . "' class='media-avatar' alt='Product Cover'>";
																	echo "</div></td>";
																	echo "<td>" . $row['title'] . "</td>";
																	echo "<td>" . $row['author'] . "</td>";
																	echo "<td>" . $row['book_id'] . "</td>";
																	echo "<td>" . $row['request_count'] . "</td>";
																	echo "</tr>";
																}
															} else {
																echo "<tr><td colspan='5'>No records found</td></tr>";
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