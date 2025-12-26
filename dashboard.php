<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$firstname = $_SESSION['firstname'] ?? '';
$lastname  = $_SESSION['lastname'] ?? '';
$role      = $_SESSION['role'] ?? '';

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT id, title, firstname, lastname, email, company, type
        FROM Contacts";

if ($filter === 'sales') {
    $sql .= " WHERE type = 'sales'";
} elseif ($filter === 'support') {
    $sql .= " WHERE type = 'support'";
} elseif ($filter === 'mine') {
    $sql .= " WHERE assigned_to = " . (int)$_SESSION['user_id'];
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body class="app-body">

<div class="top-nav">Dolphin CRM</div>

<div class="app-wrapper">

    <div class="sidebar">
        <ul>
            <li><a href="dashboard.php" class="active">Home</a></li>
            <li><a href="new_contact.php">New Contact</a></li>
            <?php if ($_SESSION['role'] === 'administrator'): ?>
                <li><a href="view_users.php">Users</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">

        <div class="card">

            <div class="card-header">
                <div class="card-title">Dashboard</div>
                <a href="new_contact.php" class="btn-primary">+ Add Contact</a>
            </div>

            <div class="filters">
                <a href="dashboard.php?filter=all">All</a>
                <a href="dashboard.php?filter=sales">Sales Leads</a>
                <a href="dashboard.php?filter=support">Support</a>
                <a href="dashboard.php?filter=mine">Assigned to me</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title'].' '.$row['firstname'].' '.$row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['company']) ?></td>
                            <td>
                                <?php
                                    $typeLabel = $row['type'] === 'sales' ? 'Sales Lead' : 'Support';
                                    $typeClass = $row['type'] === 'sales' ? 'sales-lead' : 'support';
                                ?>
                                <span class="badge <?= $typeClass ?>">
                                    <?= $typeLabel ?>
                                </span>
                            </td>

                            <td>
                                <a href="view_contact.php?id=<?= $row['id'] ?>">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No contacts found.</td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>

    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>