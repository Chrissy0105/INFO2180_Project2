 <!-- FILES -->
        <?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Files</div>
                <a href="upload_file.php" class="btn-primary">+ Upload File</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>File Name</th><th>Contact</th><th>Uploaded By</th><th>Uploaded At</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($files_result && $files_result->num_rows > 0): ?>
                        <?php while($file = $files_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($file['filename']) ?></td>
                            <td><?= htmlspecialchars($file['contact_fname'].' '.$file['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($file['uploaded_fname'].' '.$file['uploaded_lname']) ?></td>
                            <td><?= htmlspecialchars($file['uploaded_at']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($file['path']) ?>" target="_blank">View</a> |
                                <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('Delete this file?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No files uploaded.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>
