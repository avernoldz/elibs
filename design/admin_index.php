
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
											<a href="admin_archivestudent.php" >Archive Accounts</a>
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
										<li>
											<a href="admin_archivedbooks.php">Archived Books</a>
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
										<li>
											<a href="admin_archivedthesis.php">Archived Thesis</a>
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


				

								<!-- Row start -->
						<div class="row">
							<div class="col-xxl-9  col-sm-12 col-12">

								<div class="card">
									<div class="card-body">

										<!-- Row start -->
										<div class="row">
											<div class="col-xxl-3 col-sm-4 col-12">
												<div class="reports-summary">
													<div class="reports-summary-block">
														<i class="bi bi-circle-fill text-primary me-2"></i>
														<div class="d-flex flex-column">
															<h6>Overall Sales</h6>
															<h5>12 Millions</h5>
														</div>
													</div>
													<div class="reports-summary-block">
														<i class="bi bi-circle-fill text-success me-2"></i>
														<div class="d-flex flex-column">
															<h6>Overall Earnings</h6>
															<h5>78 Millions</h5>
														</div>
													</div>
													<div class="reports-summary-block">
														<i class="bi bi-circle-fill text-danger me-2"></i>
														<div class="d-flex flex-column">
															<h6>Overall Revenue</h6>
															<h5>60 Millions</h5>
														</div>
													</div>
													<div class="reports-summary-block">
														<i class="bi bi-circle-fill text-warning me-2"></i>
														<div class="d-flex flex-column">
															<h6>New Customers</h6>
															<h5>23k</h5>
														</div>
													</div>
													<button class="btn btn-info download-reports">View Reports</button>
												</div>
											</div>
											<div class="col-xxl-9 col-sm-8 col-12">
												<div class="row">
													<div class="col-12">
														<div class="graph-day-selection mt-2" role="group">
															<button type="button" class="btn active">Today</button>
															<button type="button" class="btn">Yesterday</button>
															<button type="button" class="btn">7 days</button>
															<button type="button" class="btn">15 days</button>
															<button type="button" class="btn">30 days</button>
														</div>
													</div>
													<div class="col-12">
														<div id="revenueGraph"></div>
													</div>
												</div>
											</div>
										</div>
										<!-- Row end -->

									</div>
								</div>

							</div>
							<div class="col-xxl-3  col-sm-12 col-12">

								<div class="card">
									<div class="card-header">
										<div class="card-title">Sales</div>
									</div>
									<div class="card-body">
										<div id="salesGraph" class="auto-align-graph"></div>
										<div class="num-stats">
											<h2>2100</h2>
											<h6 class="text-truncate">12% higher than last month.</h6>
										</div>
									</div>
								</div>

							</div>
						</div>
						<!-- Row end -->

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