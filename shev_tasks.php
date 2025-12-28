 <!-- TASKS -->
        <?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Tasks</div>
                <a href="new_task.php" class="btn-primary">+ Add Task</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th><th>Contact</th><th>Assigned To</th><th>Status</th><th>Due Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tasks_result && $tasks_result->num_rows > 0): ?>
                        <?php while($task = $tasks_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= htmlspecialchars($task['contact_fname'].' '.$task['contact_lname']) ?></td>
                                <td><?= htmlspecialchars($task['assigned_fname'].' '.$task['assigned_lname']) ?></td>
                                <td><?= htmlspecialchars($task['status']) ?></td>
                                <td><?= htmlspecialchars($task['due_date']) ?></td>
                                <td>
                                    <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a> |
                                    <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No tasks found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>

