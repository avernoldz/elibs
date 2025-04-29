
<?php
    include"connection.php";

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

			<!-- Include SweetAlert2 Library -->
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


          


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
							<a  href="admin_index.php">
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
											<a href="admin_achivestudent.php" >Archive Accounts</a>
											</li>
											<li>
												<a href="admin_+addstudent.php">Add Student</a>
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
										<span class="menu-text">Book Management </span>
									</a>
									<div class="sidebar-submenu ">
										<ul>
											<li>
												<a href="admin_bookcatalog.php">Book Catalog</a>
											</li>
											<li>
												<a href="admin_addbooks.php" >Add Books</a>
											</li>
											
											<li>
												<a href="admin_requests.php">View Book Requests</a>
											</li>
											<li>
												<a href="admin_returned_books.php">Returned Books</a>
											</li>
												
										</ul>
										<li class="sidebar-dropdown active ">
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
											<a href="admin_addthesis.php" class="current-page" >Add Thesis</a>
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
							<li class="breadcrumb-item breadcrumb-active" aria-current="page">Add Thesis</li>
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
								<div class="card-title">Thesis Information</div>
							</div>
							<div class="card-body">

							<div class="card-border">
													<div class="card-border-title">General Information</div>
													<div class="card-border-body">
							<div class="card-body">
							
							<!-- Add Book Form -->
                            <form method="POST" enctype="multipart/form-data" action="add_thesis.php">
							<div class="row">
								<div class="col-md-6 mb-3">
									<label for="inputThesisTitle" class="form-label">Thesis Title <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="inputThesisTitle" name="thesis_title" placeholder="Enter Thesis Title" required>
								</div>
								<div class="col-md-6 mb-3">
									<label for="inputAuthor" class="form-label">Author(s) <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="inputAuthor" name="author" placeholder="Enter Author(s) Name(s)" required>
								</div>
								<div class="col-md-6 mb-3">
									<label for="inputAdvisor" class="form-label">Thesis Advisor <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="inputAdvisor" name="advisor" placeholder="Enter Thesis Advisor's Name" required>
								</div>
								<div class="col-md-6 mb-3">
									<label for="inputStrand" class="form-label">Academic Strand <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="inputStrand" name="strand" placeholder="Enter Academic Strand" required>
								</div>
								<div class="col-md-6 mb-3">
									<label for="inputYear" class="form-label">Year of Completion <span class="text-red">*</span></label>
									<input type="number" class="form-control" id="inputYear" name="completion_year" min="1800" max="2024" placeholder="Enter Year of Completion" required>
								</div>
								<div class="col-md-6 mb-3">
									<label for="inputBookshelfCode" class="form-label">Library Code <span class="text-red">*</span></label>
									<input type="text" class="form-control" id="inputBookshelfCode" name="bookshelf_code" placeholder="Enter Library Code" required>
								</div>
								<div class="col-md-12 mb-3">
									<label for="abstractImage" class="form-label">Upload Abstract Image <span class="text-red">(5MB max)</span></label>
									<input type="file" class="form-control" id="abstractImage" name="abstract_image" accept="image/*" required>
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

							<?php
				// Check the status from the URL
				if (isset($_GET['status'])) {
					$status = $_GET['status'];
					echo "<script>
						window.onload = function() {
							switch ('$status') {
								case 'success':
									Swal.fire({
										icon: 'success',
										title: 'Success',
										text: 'Thesis added successfully!',
									});
									break;
								case 'duplicate':
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: 'A thesis with this title already exists.',
									});
									break;
								case 'file_error':
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: 'There was an error uploading the abstract image. Please try again.',
									});
									break;
								case 'error':
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: 'There was an error adding the thesis. Please try again.',
									});
									break;
							}
						};
					</script>";
				}
				?>


			

		</body>
		

	</html>