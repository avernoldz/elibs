<?php
include 'connection.php';

$books_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $books_per_page;

// Get total count for pagination
$total_query = "SELECT COUNT(*) as total FROM archived_books";
$total_result = $conn->query($total_query);
$total_books = $total_result ? $total_result->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_books / $books_per_page);

// Fetch paginated data
$sql = "SELECT * FROM archived_books LIMIT ?, ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ii", $offset, $books_per_page);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        $result = false;
        error_log("Execution error: " . $stmt->error);
    }
} else {
    $result = false;
    error_log("Preparation error: " . $conn->error);
}
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

<!-- Card start -->
<div class="card">
    <div class="card-header">
        <div class="card-title">Archived Books</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="archivedBooksTable" class="table custom-table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Publisher</th>
                        <th>Pub. Year</th>
                        <th>Edition</th>
                        <th>Quantity</th>
                        <th>Shelf</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?= htmlspecialchars($row['book_image_path'] ?: 'default_cover.jpg') ?>" alt="cover" style="width: 50px; height: 50px; object-fit: cover;"></td>
                                <td><?= htmlspecialchars($row['book_id']) ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['author']) ?></td>
                                <td><?= htmlspecialchars($row['isbn']) ?></td>
                                <td><?= htmlspecialchars($row['publisher']) ?></td>
                                <td><?= htmlspecialchars($row['publication_year']) ?></td>
                                <td><?= htmlspecialchars($row['edition']) ?></td>
                                <td><?= number_format((int)$row['quantity']) ?></td>
                                <td><?= htmlspecialchars($row['bookshelf_code']) ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm restoreBook" data-id="<?= $row['book_id'] ?>">
                                        Restore
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="11" class="text-center">No archived books found or a database error occurred.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination justify-content-center mt-4">
            <ul class="pagination">
                <?php
                if ($current_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="archived_books.php?page=' . ($current_page - 1) . '">Previous</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
                }
                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i == $current_page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="archived_books.php?page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                if ($current_page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="archived_books.php?page=' . ($current_page + 1) . '">Next</a></li>';
                } else {
                    echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<!-- Card end -->

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
        $('#archivedBooksTable').DataTable({
            paging: false, // Disable internal paging
            ordering: true,
            info: false
        });

        $('.restoreBook').on('click', function () {
            const bookId = $(this).data('id');
            Swal.fire({
                title: 'Restore Book?',
                text: 'This book will be returned to the active catalog.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Restore it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `restore_book.php?book_id=${bookId}`;
                }
            });
        });

        $('#exportPdf').on('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text('📚 Archived Books', 14, 15);
            doc.autoTable({
                html: '#archivedBooksTable',
                startY: 20,
                headStyles: { fillColor: [220, 53, 69] },
                styles: { fontSize: 9 },
                theme: 'grid'
            });
            doc.save('archived_books.pdf');
        });
    });
</script>

<?php $conn->close(); ?>
