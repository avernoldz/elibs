<?php
include 'connection.php';

// Filters from query string
$monthFilter = $_GET['month'] ?? '';
$gradeFilter = $_GET['grade'] ?? '';

// Build WHERE clause dynamically
$whereClause = [];
if (!empty($monthFilter)) {
    $whereClause[] = "MONTH(br.return_date) = " . intval($monthFilter);
}
if (!empty($gradeFilter)) {
    $whereClause[] = "s.grade_level = " . intval($gradeFilter);
}
$whereSQL = !empty($whereClause) ? "WHERE " . implode(' AND ', $whereClause) : "";

// Query
$sql = "SELECT br.request_id, br.request_date, br.status, br.expected_pickup_date, br.due_date, br.return_date, br.is_returned,
               b.book_id, b.title AS book_title, b.author, b.isbn, b.publisher, b.publication_year, b.edition, b.quantity, b.bookshelf_code,
               s.name AS student_name, s.grade_level
        FROM book_requests br
        JOIN books b ON br.book_id = b.book_id
        JOIN students s ON br.student_id = s.student_id
        $whereSQL";

$result = $conn->query($sql);
?>

<!-- Card Start -->
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
        <div class="card-title"> Book Return History</div>
        <div class="d-flex flex-wrap gap-2 align-items-center">

            <!-- Filters -->
            <select id="monthFilter" class="form-select form-select-sm">
                <option value="">Filter by Month</option>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= ($monthFilter == $m) ? 'selected' : '' ?>>
                        <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>

            <select id="gradeFilter" class="form-select form-select-sm">
                <option value="">Filter by Grade Level</option>
                <?php
                $grades = $conn->query("SELECT DISTINCT grade_level FROM students WHERE grade_level IS NOT NULL ORDER BY grade_level");
                while ($grade = $grades->fetch_assoc()):
                ?>
                    <option value="<?= $grade['grade_level'] ?>" <?= ($gradeFilter == $grade['grade_level']) ? 'selected' : '' ?>>
                        Grade <?= $grade['grade_level'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Buttons -->
            <button type="button" class="btn btn-success btn-sm me-2"
                    onclick="window.location.href='admin_bookreturned.php'"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Return Book">
                <i class="bi bi-plus-circle"></i> 
            </button>

            <button type="button" class="btn btn-danger btn-sm" id="exportPdf">
                <i class="bi bi-file-earmark-pdf-fill"></i> 
            </button>

            <button type="button" class="btn btn-secondary btn-sm" onclick="openPrintView()">
    <i class="bi bi-printer-fill"></i> 
</button>

        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="returnHistoryTable" class="table custom-table table-striped">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Grade Level</th>
                        <th>Book Title</th>
                        <th>Request Date</th>
                        <th>Returned Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['grade_level']) ?></td>
                                <td><?= htmlspecialchars($row['book_title']) ?></td>
                                <td><?= date('m/d/Y', strtotime($row['request_date'])) ?></td>
                                <td><?= !empty($row['return_date']) ? date('m/d/Y', strtotime($row['return_date'])) : '—' ?></td>
                                <td>
                                    <?php if ($row['is_returned']): ?>
                                        <span class="text-success"><i class="bi bi-check-circle"></i> Returned</span>
                                    <?php else: ?>
                                        <span class="text-danger"><i class="bi bi-clock-history"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-link viewRow"
                                        data-title="<?= htmlspecialchars($row['book_title']) ?>"
                                        data-author="<?= htmlspecialchars($row['author']) ?>"
                                        data-isbn="<?= htmlspecialchars($row['isbn']) ?>"
                                        data-publisher="<?= htmlspecialchars($row['publisher']) ?>"
                                        data-year="<?= htmlspecialchars($row['publication_year']) ?>"
                                        data-edition="<?= htmlspecialchars($row['edition']) ?>"
                                        data-quantity="<?= htmlspecialchars($row['quantity']) ?>"
                                        data-bookshelf="<?= htmlspecialchars($row['bookshelf_code']) ?>"
                                        title="View Book">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No book return records found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Card End -->

<!-- Styling -->
<style>
    .btn-link i {
        font-size: 1.2rem;
        color: #0d6efd;
    }
    .btn-link i:hover {
        color: #0a58ca;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
    $(document).ready(function () {
        $('#returnHistoryTable').DataTable();

        $('#monthFilter, #gradeFilter').on('change', function () {
            const month = $('#monthFilter').val();
            const grade = $('#gradeFilter').val();
            window.location.href = `?month=${month}&grade=${grade}`;
        });

        $('.viewRow').on('click', function () {
            Swal.fire({
                title: '📖 Book Details',
                html: `
                    <div class="text-start">
                        <p><strong>Title:</strong> ${$(this).data('title')}</p>
                        <p><strong>Author:</strong> ${$(this).data('author')}</p>
                        <p><strong>ISBN:</strong> ${$(this).data('isbn')}</p>
                        <p><strong>Publisher:</strong> ${$(this).data('publisher')}</p>
                        <p><strong>Year:</strong> ${$(this).data('year')}</p>
                        <p><strong>Edition:</strong> ${$(this).data('edition')}</p>
                        <p><strong>Quantity:</strong> ${$(this).data('quantity')}</p>
                        <p><strong>Bookshelf:</strong> ${$(this).data('bookshelf')}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#0d6efd',
                width: 600
            });
        });

        $('#exportPdf').on('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.text(' Book Return History', 14, 15);
            doc.autoTable({
                html: '#returnHistoryTable',
                startY: 20,
                headStyles: { fillColor: [40, 167, 69] },
                styles: { fontSize: 9 },
                theme: 'grid'
            });

            doc.save('Returnedbook.pdf');
        });
    });
    function openPrintView() {
    const month = document.getElementById('monthFilter').value;
    const grade = document.getElementById('gradeFilter').value;
    const url = `print_returned_books.php?month=${month}&grade=${grade}`;
    window.open(url, '_blank');
}

</script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
