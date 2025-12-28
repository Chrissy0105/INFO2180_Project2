 <!-- CASES -->
        <?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Cases</div>
                <a href="new_case.php" class="btn-primary">+ New Case</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th><th>Contact</th><th>Status</th><th>Created At</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cases_result && $cases_result->num_rows > 0): ?>
                        <?php while($case = $cases_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($case['title']) ?></td>
                            <td><?= htmlspecialchars($case['contact_fname'].' '.$case['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($case['status']) ?></td>
                            <td><?= htmlspecialchars($case['created_at']) ?></td>
                            <td>
                                <a href="edit_case.php?id=<?= $case['id'] ?>">Edit</a> |
                                <a href="delete_case.php?id=<?= $case['id'] ?>" onclick="return confirm('Delete this case?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No cases found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>
