<?php
include 'connection.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Invalid thesis ID.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'admin_thesiscatalog.php';
        });
    </script>";
    exit();
}

$thesis_id = $_GET['id'];
$error_message = "";

// Fetch existing thesis data
$query = "SELECT * FROM thesis WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $thesis_id);
$stmt->execute();
$result = $stmt->get_result();
$thesis = $result->fetch_assoc();
$stmt->close();

if (!$thesis) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Thesis not found.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'admin_thesiscatalog.php';
        });
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $advisor = $_POST['advisor'];
    $strand = $_POST['strand'];
    $completion_year = $_POST['completion_year'];
    $bookshelf_code = $_POST['bookshelf_code'];

    // Handle file upload
    $image_path = $thesis['abstract_image']; // Keep old image by default
    if (!empty($_FILES['abstract_image']['name'])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["abstract_image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size (limit to 5MB)
        if ($_FILES["abstract_image"]["size"] > 5 * 1024 * 1024) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'File size too large (max 5MB).',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
            exit();
        } else {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["abstract_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error uploading file.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
                exit();
            }
        }
    }

    // Update query
    $update_query = "UPDATE thesis SET title=?, author=?, advisor=?, strand=?, completion_year=?, bookshelf_code=?, abstract_image=? WHERE id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssssi", $title, $author, $advisor, $strand, $completion_year, $bookshelf_code, $image_path, $thesis_id);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Thesis updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'admin_thesiscatalog.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error updating thesis.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
    $stmt->close();
}

$conn->close();
?>


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
								<div class="card-title">Edit Thesis Information</div>
							</div>
							<div class="card-body">

							<div class="card-border">
													<div class="card-border-title">General Information</div>
													<div class="card-border-body">
							<div class="card-body">
							
							<!-- Add Book Form -->
                            <div class="container mt-4">
                            

                            <?php if (!empty($error_message)) : ?>
                                <div class="alert alert-danger"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="inputThesisTitle" class="form-label">Thesis Title <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inputThesisTitle" name="title" value="<?php echo htmlspecialchars($thesis['title']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inputAuthor" class="form-label">Author(s) <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inputAuthor" name="author" value="<?php echo htmlspecialchars($thesis['author']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inputAdvisor" class="form-label">Thesis Advisor <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inputAdvisor" name="advisor" value="<?php echo htmlspecialchars($thesis['advisor']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inputStrand" class="form-label">Academic Strand <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inputStrand" name="strand" value="<?php echo htmlspecialchars($thesis['strand']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inputYear" class="form-label">Year of Completion <span class="text-red">*</span></label>
            <input type="number" class="form-control" id="inputYear" name="completion_year" min="1800" max="2024" value="<?php echo htmlspecialchars($thesis['completion_year']); ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="inputBookshelfCode" class="form-label">Library Code <span class="text-red">*</span></label>
            <input type="text" class="form-control" id="inputBookshelfCode" name="bookshelf_code" value="<?php echo htmlspecialchars($thesis['bookshelf_code']); ?>" required>
        </div>
        <div class="col-md-12 mb-3">
            <label for="abstractImage" class="form-label">Upload New Abstract Image (Optional) <span class="text-red">(5MB max)</span></label>
            <input type="file" class="form-control" id="abstractImage" name="abstract_image" accept="image/*">
        </div>
    </div>
    <div class="form-actions-footer">
        <div class="col-md-12 text-end">
            <a href="admin_thesiscatalog.php" class="btn btn-light">Cancel</a>
            <button type="submit" class="btn btn-success">Update Thesis</button>
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

							
			

		</body>
		

	</html>