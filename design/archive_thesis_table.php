<div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Archived Theses</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table v-middle m-0 thesis-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author(s)</th>
                                <th>Advisor</th>
                                <th>Strand</th>
                                <th>Year</th>
                                <th>Bookshelf</th>
                                <th>Archived At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td><?= htmlspecialchars($row['author']) ?></td>
                                        <td><?= htmlspecialchars($row['advisor']) ?></td>
                                        <td><?= htmlspecialchars($row['strand']) ?></td>
                                        <td><?= htmlspecialchars($row['completion_year']) ?></td>
                                        <td><?= htmlspecialchars($row['bookshelf_code']) ?></td>
                                        <td><?= htmlspecialchars($row['archived_at']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-success restoreBtn" data-id="<?= $row['archived_id'] ?>" title="Restore Thesis">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No archived theses found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
             
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.restoreBtn').forEach(button => {
            button.addEventListener('click', () => {
                const archiveId = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Restore Thesis',
                    text: 'Are you sure you want to restore this thesis to the catalog?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = `restore_thesis.php?archived_id=${archiveId}`;
                    }
                });
            });
        });
    });
</script>
