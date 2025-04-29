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

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
	<!-- *************
			************ Vendor Css Files *************
		************ -->

	<!-- Scrollbar CSS -->
	<link rel="stylesheet" href="assets/vendor/overlay-scroll/OverlayScrollbars.min.css">

	<!-- tagsCloud Keywords CSS -->
	<link rel="stylesheet" href="assets/vendor/tagsCloud/tagsCloud.css" />

</head>
<!-- Show Success or Error Alert -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($successMessage): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $successMessage; ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        <?php elseif ($errorMessage): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $errorMessage; ?>',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    });
</script>

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
						<li class="sidebar-dropdown">
							<a href="#">
								<i class="bi bi-people"></i>
								<span class="menu-text">Administration</span>
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
						<li class="sidebar-dropdown ">
							<a href="#">
								<i class="bi bi-bookshelf"></i>
								<span class="menu-text">Book</span>
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
									<a href="#"></a>
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
									<a href="admin_thesiscatalog.php">Thesis Catalog</a>
								</li>
								<li>
									<a href="admin_addthesis.php">Add Thesis</a>
								</li>


								<li>
									<a href="#">Returned Books</a>
								</li>

							</ul>
						</div>
					<li class="active-page-link">
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
			<!-- Page header ends -->

			<!-- Content wrapper scroll start -->
			<div class="content-wrapper-scroll">

				<!-- Content wrapper start -->
				<div class="content-wrapper">

				<?php

include 'connection.php';

// Fetch the admin details (Replace with session-based admin ID)
$adminID = $_SESSION['admin_id'] ?? 1;
$query = "SELECT * FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $adminID);
$stmt->execute();
$result = $stmt->get_result();
$adminData = $result->fetch_assoc();
$stmt->close();

// Set a default avatar if not available
$adminAvatar = !empty($adminData['avatar']) ? 'uploads/' . htmlspecialchars($adminData['avatar']) : 'assets/images/default-avatar.png';

// Handle session messages (Success/Error)
$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!-- Row start -->
<div class="row">
    <div class="col-xl-12">
        <!-- Card start -->
        <div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <img src="<?php echo $adminAvatar; ?>" class="img-fluid rounded-circle mb-3" width="150" height="150" alt="Admin Avatar">
                    
                    <!-- Avatar Upload Form -->
                    <form action="upload_avatar.php" method="POST" enctype="multipart/form-data" class="text-center">
                        <input type="file" name="avatar" accept="image/*" required class="form-control mt-2">
                        <button type="submit" class="btn btn-primary mt-2">Change Image</button>
                    </form>
                </div>

                <div class="col-md-8">
                    <!-- Profile Update Form -->
                    <form id="profileForm" action="save_settings.php" method="POST">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            <div class="col">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName"
                                    value="<?php echo htmlspecialchars($adminData['full_name']); ?>" placeholder="Full Name">
                            </div>

                            <div class="col">
                                <label for="emailID" class="form-label">Email ID</label>
                                <input type="email" class="form-control" id="emailID" name="emailID"
                                    value="<?php echo htmlspecialchars($adminData['email']); ?>" placeholder="admin@example.com">
                            </div>

                            <div class="col">
                                <label for="userName" class="form-label">User Name</label>
                                <input type="text" class="form-control" id="userName" name="userName"
                                    value="<?php echo htmlspecialchars($adminData['username']); ?>" placeholder="User Name">
                            </div>

                            <div class="col">
                                <label for="phoneNo" class="form-label">Phone</label>
                                <input type="number" class="form-control" id="phoneNo" name="phoneNo"
                                    value="<?php echo htmlspecialchars($adminData['phone_number']); ?>" placeholder="123-456-7890">
                            </div>

                            <div class="col">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="<?php echo htmlspecialchars($adminData['address']); ?>" placeholder="Address">
                            </div>

                            <div class="col">
                                <label for="enterPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="enterPassword" name="enterPassword"
                                    placeholder="Enter New Password (Leave blank to keep current)">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-info w-100" onclick="confirmUpdate()">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>
<!-- Row end -->

<?php
// Close the database connection
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

	<!-- Date Range JS -->
	<script src="assets/vendor/daterange/daterange.js"></script>
	<script src="assets/vendor/daterange/custom-daterange.js"></script>

	<!-- Dropzone JS -->
	<script src="assets/vendor/dropzone/dropzone.min.js"></script>

	<!-- Main Js Required -->
	<script src="assets/js/main.js"></script>

	<script>
function confirmUpdate() {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to save these changes?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Save!",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("profileForm").submit();
        }
    });
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let successMessage = <?php echo isset($_SESSION['success_message']) ? json_encode($_SESSION['success_message']) : 'null'; ?>;
    let errorMessage = <?php echo isset($_SESSION['error_message']) ? json_encode($_SESSION['error_message']) : 'null'; ?>;

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: successMessage,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['success_message']); ?>
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: errorMessage,
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
        <?php unset($_SESSION['error_message']); ?>
    }
});
</script>

</body>

</html>