<?php
session_start();

/* Admin-only access */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login.php");
    exit();
}

/* Database connection (inline, as you chose) */
$conn = new mysqli("localhost", "root", "", "dolphin_crm");

if ($conn->connect_error) {
    die("Database connection failed.");
}

/* Fetch users (NO passwords) */
$sql = "SELECT firstname, lastname, email, role, created_at FROM USERS ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
            <div class="card">
                <div class="card-title">Users</div>

                <?php if ($result && $result->num_rows > 0): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($row['role'])) ?></td>
                                    <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($row['created_at']))) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="feedback">No users found.</p>
                <?php endif; ?>

            </div>

<?php
$conn->close();
?>
