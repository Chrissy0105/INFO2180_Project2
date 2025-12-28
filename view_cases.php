<?php
session_start();

// Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'] ?? '';

// Database connection
$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

// Fetch all cases with contact info
$sql = "SELECT cs.*, c.firstname AS contact_fname, c.lastname AS contact_lname
        FROM Cases cs
        LEFT JOIN Contacts c ON cs.contact_id = c.id
        ORDER BY cs.created_at DESC";

$result = $conn->query($sql);
?>

        <section class="card">
            <div class="card-header">
                <div class="card-title">All Cases</div>
                <a href="new_case.php" class="btn-primary">+ Add Case</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <?php if ($role === 'administrator'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($case = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($case['title']) ?></td>
                            <td><?= htmlspecialchars($case['contact_fname'] . ' ' . $case['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($case['status']) ?></td>
                            <td><?= htmlspecialchars($case['created_at']) ?></td>
                            <?php if ($role === 'administrator'): ?>
                                <td>
                                    <a href="edit_case.php?id=<?= $case['id'] ?>">Edit</a> |
                                    <a href="delete_case.php?id=<?= $case['id'] ?>" onclick="return confirm('Delete this case?')">Delete</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="<?= $role === 'administrator' ? 5 : 4 ?>">No cases found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>


<?php $conn->close(); ?>
