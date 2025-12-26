<?php
session_start();

/* Admin-only access */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login.php");
    exit();
}

/* Database connection (inline, as you chose) */
$conn = new mysqli("localhost", "root", "Jamaicakl#1", "dolphin_crm");

if ($conn->connect_error) {
    die("Database connection failed.");
}

/* Fetch users (NO passwords) */
$sql = "SELECT firstname, lastname, email, role, created_at FROM USERS ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – Users</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body class="app-body">

    <div class="top-nav">Dolphin CRM</div>

    <div class="app-wrapper">
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="new_user.php">New Contact</a></li>
                <li><a href="view_users.php" class="active">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
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
        </main>
    </div>

</body>
</html>

<?php
$conn->close();
?>
