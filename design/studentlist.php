    <?php
    // Database connection
    include 'connection.php';

    // Initialize search query
    $search_query = '';

    // Number of students to display per page
    $students_per_page = 5;

    // Get the current page from the URL, if not set, default to 1
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the starting record for the current page
    $offset = ($current_page - 1) * $students_per_page;

    // Check if search is performed
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
    }

    // Function to generate pagination links
    function renderPagination($total_pages, $current_page, $search_query = '') {
        $search_param = !empty($search_query) ? '&search=' . urlencode($search_query) : '';

        echo '<div class="pagination justify-content-center mt-4">';

        // Previous button
        if ($current_page > 1) {
            echo '<li class="page-item"><a class="page-link" href="admin_studentlist.php?page=' . ($current_page - 1) . $search_param . '">Previous</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="admin_studentlist.php?page=' . $i . $search_param . '">' . $i . '</a></li>';
            }
        }

        // Next button
        if ($current_page < $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="admin_studentlist.php?page=' . ($current_page + 1) . $search_param . '">Next</a></li>';
        } else {
            echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        echo '</div>';
    }

    // Prepare the SQL query based on whether a search term is provided
    if (!empty($search_query)) {
        // Count total students for pagination
        $query = "SELECT COUNT(*) FROM students WHERE name LIKE ?";
        $stmt = $conn->prepare($query);
        $search_param = '%' . $search_query . '%';
        $stmt->bind_param("s", $search_param);
    } else {
        $query = "SELECT COUNT(*) FROM students";
        $stmt = $conn->prepare($query);
    }

    // Execute the count query
    $stmt->execute();
    $stmt->bind_result($total_students);
    $stmt->fetch();
    $stmt->close();

    // Calculate total pages
    $total_pages = ceil($total_students / $students_per_page);

        // Fetch students for the current page
    if (!empty($search_query)) {
        $query = "SELECT lrn, name, birthday, email, grade_level, section, status 
                FROM students 
                WHERE name LIKE ? OR lrn LIKE ? 
                LIMIT ?, ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssii", $search_param, $search_param, $offset, $students_per_page);
    } else {
        $query = "SELECT lrn, name, birthday, email, grade_level, section, status 
                FROM students 
                LIMIT ?, ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $offset, $students_per_page);
    }

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div="row">
        <div class="col-sm-12 col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title">Student List</div>
                    <div class="d-flex align-items-center">
                    


                        <div class="search-container">
                            <!-- Add Student Button -->
                            <button type="button" class="btn btn-success btn-icon me-1" onclick="window.location.href='admin_addstudent.php'" data-bs-toggle="tooltip" data-bs-placement="top" title="Add New Student">
                                <i class="bi bi-plus-circle"></i> 
                            </button>
                            <button type="button" class="btn btn-info btn-icon me-2" onclick="downloadTableAsPDF()" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Download as PDF">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-icon me-2" onclick="printPage()" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Print">
                                <i class="bi bi-printer"></i>
                            </button> 
                            <div class="input-group">
                                <form action="admin_studentlist.php" method="GET">
                                    <input type="text" class="form-control" name="search" placeholder="Search Name or LRN" value="<?php echo htmlspecialchars($search_query); ?>">
                                    <button class="btn" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table v-middle m-0">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Name</th>
                                    <th>Date of Birth</th>
                                    <th>Email</th>
                                    <th>Grade</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['lrn']); ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['birthday']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['grade_level']); ?></td>
                                            <td><?php echo htmlspecialchars($row['section']); ?></td>
                                            <td>
                                                <?php if ($row['status'] == 1) { ?>
                                                    <span class="badge shade-green min-70">Active</span>
                                                <?php } else { ?>
                                                    <span class="badge shade-red min-70">Blocked</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <!-- Edit button -->
                                                    <a href="#" class="editRow" data-toggle="modal" data-target="#editStudentModal"
                                                    data-lrn="<?php echo htmlspecialchars($row['lrn']); ?>"
                                                    data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                                    data-birthday="<?php echo htmlspecialchars($row['birthday']); ?>"
                                                    data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                                    data-grade="<?php echo htmlspecialchars($row['grade_level']); ?>"
                                                    data-section="<?php echo htmlspecialchars($row['section']); ?>"
                                                    data-status="<?php echo htmlspecialchars($row['status']); ?>">
                                                        <i class="bi bi-pencil text-blue"></i>
                                                    </a>
                                                    <!-- Deactivate/Activate button -->
                                                    <!-- Activation/Deactivation Links -->
                                                    
                                                            <?php if ($row['status'] == 1) { ?>
                                                            <a href="   
                                                                <button type="button" class="btn btn-link deactivateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="deactivate" data-id="<?php echo $row['lrn']; ?>">
                                                                    <i class="bi bi-x-circle text-red"></i>
                                                                </button>
                                                                </a>
                                                            <?php } else { ?>
                                                                <a href="
                                                                <button type="button" class="btn btn-link activateRow" data-bs-toggle="modal" data-bs-target="#modalDark" data-action="activate" data-id="<?php echo $row['lrn']; ?>">
                                                                    <i class="bi bi-check-circle text-green"></i>
                                                                </button>
                                                                </a>
                                                            <?php } ?>

                                                            
                                                </div>
                                                
                                                
                                            </td>

                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No students found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Render Pagination -->
                    <?php renderPagination($total_pages, $current_page, $search_query); ?>

    </div>
    </div>
    </div>
                            </div>
</div>

                            <?php
// Close the database connection
$conn->close();
?>

    <!-- Edit Student Modal -->
<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                
            </div>
            <div class="modal-body">
                <form id="editStudentForm" method="POST" action="update_student.php">
    <input type="hidden" name="lrn" id="edit-lrn"> <!-- Hidden field for the student ID -->
                    <!-- Row start -->
                    <div class="row">
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="edit-name" placeholder="Enter Name" value="" disabled="">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="mb-3">
                                <label for="edit-email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit-email" placeholder="Enter Email">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="mb-3">
                                <label for="edit-birthday" class="form-label">Date of Birth</label>
                                <input type="text" class="form-control" name="birthday" id="edit-birthday" value="Disabled Field" disabled="">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="mb-3">
                                <label for="edit-grade" class="form-label">Grade Level</label>
                                <select class="form-select" name="grade_level" id="edit-grade">
                                    <option value="">Select Grade Level</option>
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12 col-12">
                            <div class="mb-3">
                                <label for="edit-section" class="form-label">Section</label>
                                <input type="text" class="form-control" name="section" id="edit-section" placeholder="Enter Section">
                            </div>
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- Form actions footer start -->
                    <div class="form-actions-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    <!-- Form actions footer end -->
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Fade -->
<div class="modal fade" id="modalDark" tabindex="-1" aria-labelledby="modalDarkLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDarkLabel">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Are you sure you want to proceed with this action?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // When the edit button is clicked, populate the modal with the correct student's data
    $('.editRow').click(function() {
        var lrn = $(this).data('lrn'); // Student ID
        var name = $(this).data('name');
        var email = $(this).data('email');
        var birthday = $(this).data('birthday');
        var grade_level = $(this).data('grade');
        var section = $(this).data('section');

        // Populate the modal with the student's data
        $('#edit-lrn').val(lrn); // Hidden field for student ID
        $('#edit-name').val(name); // Name field
        $('#edit-email').val(email); // Email field
        $('#edit-birthday').val(birthday); // Birthday field
        $('#edit-grade').val(grade_level); // Grade field
        $('#edit-section').val(section); // Section field
    });

    // Reset modal data when closed
    $('#editStudentModal').on('hidden.bs.modal', function() {
        $('#editStudentForm')[0].reset(); // Clear all form fields in the modal
    });
});

</script>

    <script>
        const modalMessage = document.getElementById('modalMessage');
        const confirmButton = document.getElementById('confirmAction');

        document.addEventListener('DOMContentLoaded', () => {
            const deactivateButtons = document.querySelectorAll('.deactivateRow, .activateRow');

            deactivateButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const action = this.getAttribute('data-action');
                    const id = this.getAttribute('data-id');

                    // Set the message based on action
                    modalMessage.textContent = `Are you sure you want to ${action} this student?`;

                    // Set the confirm button action
                    confirmButton.onclick = function () {
                        // Redirect based on the action
                        window.location.href = action === 'deactivate' 
                            ? `deactivate_student.php?id=${id}` 
                            : `activate_student.php?id=${id}`;
                    };
                });
            });
        });
    </script>
   


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>


    <script>
        function printPage() {
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print Student List</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1 class="text-center">Student List</h1>');

        var tableClone = document.querySelector('.table').cloneNode(true);
        var headers = tableClone.querySelectorAll('thead th');
        var rows = tableClone.querySelectorAll('tbody tr');

        headers[headers.length - 1].remove(); // Remove "Actions" header
        rows.forEach(function(row) {
            row.lastElementChild.remove(); // Remove "Actions" from each row
        });

        printWindow.document.write(tableClone.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print(); // Trigger print dialog
    }

    </script>

    <script>
        function downloadTableAsPDF() {
        // Clone the table and remove the last column
        var tableClone = document.querySelector('.table').cloneNode(true);
        var headers = tableClone.querySelectorAll('thead th');
        var rows = tableClone.querySelectorAll('tbody tr');

        // Remove the last header (Actions)
        headers[headers.length - 1].remove();

        // Remove the last cell (Actions) from each row
        rows.forEach(function(row) {
            row.lastElementChild.remove();
        });

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text('Student List', 14, 16);

        // Use the modified cloned table
        doc.autoTable({
            html: tableClone,
            startY: 20,
            theme: 'grid',
            headStyles: { fillColor: [100, 100, 255] },
            didDrawPage: function (data) {
                doc.text('Page ' + doc.internal.getNumberOfPages(), 14, doc.internal.pageSize.height - 10);
            }
        });

        doc.save('student_list.pdf'); // Save as PDF
    }

    </script>