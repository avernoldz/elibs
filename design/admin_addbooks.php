<?php
include "connection.php"; // Include database connection


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
						<li>
							<a href="admin_index.php">
								<i class="bi bi-house"></i>
								<span class="menu-text">Dashboards</span>
							</a>
						</li>
						<li class="sidebar-dropdown ">
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
										<a href="admin_addstudent.php">Add Student</a>
									</li>


									<li>
										<a href="admin_reviews.php">Reviews</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="sidebar-dropdown active">
							<a href="#">
								<i class="bi bi-bookshelf"></i>
								<span class="menu-text">Book Management </span>
							</a>
							<div class="sidebar-submenu ">
								<ul>
									<li>
										<a href="admin_bookcatalog.php">Book Catalog</a>
									</li>
									<li>
										<a href="admin_addbooks.php" class="current-page">Add Books</a>
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
										<a href="admin_addthesis.php">Thesis Books</a>
									</li>


									<li>
										<a href="#">Returned Thesis</a>
									</li>

								</ul>
							</div>
						<li class="sidebar-dropdown ">
							<a href="#">
								<i class="bi bi-file"></i>
								<span class="menu-text">Reports</span>
							</a>
							<div class="sidebar-submenu">
								<ul>
									<li>
										<a href="admin_studentreport.php">Student Report</a>
									</li>
									<li>
										<a href="admin_bookreport.php">Book Report</a>
									</li>
									<!-- <li>
										<a href="admin_addthesis.php">Transaction Report</a>
									</li> -->
								</ul>
							</div>
						<li>
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
						<a href="admin_index.php">Home</a>
					</li>
					<li class="breadcrumb-item breadcrumb-active" aria-current="page">Add Books</li>
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




					<div class="card">
						<div class="card-header">
							<div class="card-title">Book Information</div>
						</div>
						<div class="card-body">

							<div class="card-border">
								<div class="card-border-title">General Information</div>
								<div class="card-border-body">
									<div class="card-body">

										<!-- Add Book Form -->
										<form method="POST" enctype="multipart/form-data" action="add_books.php">
											<div class="row">
												<div class="col-md-6 mb-3">
													<label for="inputBookTitle" class="form-label">Book Title<span class="text-red">*</span></label>
													<input type="text" class="form-control" id="inputBookTitle" name="book_title" placeholder="Enter Book Title" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputAuthor" class="form-label">Author<span class="text-red">*</span></label>
													<input type="text" class="form-control" id="inputAuthor" name="author" placeholder="Enter Author" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputISBN" class="form-label">ISBN<span class="text-red">*</span></label>
													<input type="number" class="form-control" id="inputISBN" name="isbn" placeholder="Enter ISBN" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputPublisher" class="form-label">Publisher<span class="text-red">*</span></label>
													<input type="text" class="form-control" id="inputPublisher" name="publisher" placeholder="Enter Publisher" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputPublicationYear" class="form-label">Publication Year<span class="text-red">*</span></label>
													<input type="number" class="form-control" id="inputPublicationYear" name="publication_year" min="1800" max="2024" placeholder="Enter Publication Year" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputEdition" class="form-label">Edition<span class="text-red">*</span></label>
													<input type="text" class="form-control" id="inputEdition" name="edition" placeholder="Enter Edition">
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputQuantity" class="form-label">Quantity<span class="text-red">*</span></label>
													<input type="number" class="form-control" id="inputQuantity" name="quantity" min="1" placeholder="Enter Quantity" required>
												</div>
												<div class="col-md-6 mb-3">
													<label for="inputBookshelfCode" class="form-label">Bookshelf Code<span class="text-red">*</span></label>
													<input type="text" class="form-control" id="inputBookshelfCode" name="bookshelf_code" placeholder="Enter Bookshelf Code" required>
												</div>
												<div class="col-md-12 mb-3">
													<label for="bookImage" class="form-label">Upload Book Image</label>
													<input type="file" class="form-control" id="bookImage" name="book_image" accept="image/*" required>
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
							</div>
						</div>
					</div>
					<!-- Row end -->

				</div>

				<!-- Success Modal -->
				<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="successModalLabel">Success</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								BOOK ADDED SUCCESSFUL...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Duplicate ISBN Modal -->
				<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="duplicateModalLabel">Error</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								A book with that ISBN already exists.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

				<!-- General Error Modal -->
				<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="errorModalLabel">Error</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								There was an error adding the book. Please try again.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

				<!-- File Upload Error Modal -->
				<div class="modal fade" id="fileErrorModal" tabindex="-1" aria-labelledby="fileErrorModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="fileErrorModalLabel">Error</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								There was an error uploading the book image. Please try again.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>


				<!-- JS for modal trigger -->



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

	<!-- Dropzone JS -->
	<script src="assets/vendor/dropzone/dropzone.min.js"></script>

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

	<script>
		// Function to get query parameters from URL
		function getQueryParameter(param) {
			const urlParams = new URLSearchParams(window.location.search);
			return urlParams.get(param);
		}

		// Triggering modals based on status
		document.addEventListener("DOMContentLoaded", function() {
			const status = getQueryParameter('status');

			if (status === 'success') {
				// Show success modal
				const successModal = new bootstrap.Modal(document.getElementById('successModal'));
				successModal.show();
			} else if (status === 'duplicate') {
				// Show duplicate ISBN modal
				const duplicateModal = new bootstrap.Modal(document.getElementById('duplicateModal'));
				duplicateModal.show();
			} else if (status === 'file_error') {
				// Show file upload error modal
				const fileErrorModal = new bootstrap.Modal(document.getElementById('fileErrorModal'));
				fileErrorModal.show();
			} else if (status === 'error') {
				// Show general error modal
				const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
				errorModal.show();
			}
		});
	</script>





</body>


</html>