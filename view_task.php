<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'] ?? '';
$firstname = $_SESSION['firstname'] ?? '';
$lastname  = $_SESSION['lastname'] ?? '';

if ($role !== 'administrator') {
    die("Access denied. Administrators only.");
}

$conn = new mysqli("localhost", "root", "Jamaicakl#1", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$sql = "
    SELECT t.*, 
           c.firstname AS contact_fname, c.lastname AS contact_lname, 
           u.firstname AS assigned_fname, u.lastname AS assigned_lname
    FROM Tasks t
    LEFT JOIN Contacts c ON t.contact_id=c.id
    LEFT JOIN USERS u ON t.assigned_to=u.id
    ORDER BY t.due_date ASC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dolphin CRM – Tasks</title>
<link rel="stylesheet" href="dashboard.css">
</head>
<body class="app-body">

<div class="top-nav">Dolphin CRM</div>

<div class="app-wrapper">

    <aside class="sidebar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="new_contact.php">New Contact</a></li>
            <li><a href="view_contacts.php">Contacts</a></li>
            <li><a href="add_note.php">Notes</a></li>
            <li><a href="new_user.php">New User</a></li>
            <li><a href="view_users.php">Users</a></li>
            <li><a href="view_task.php" class="active">Tasks</a></li>
            <li><a href="new_case.php">Cases</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">All Tasks</div>
                <a href="new_task.php" class="btn-primary">+ Add New Task</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Contact</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($task = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= htmlspecialchars($task['contact_fname'].' '.$task['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($task['assigned_fname'].' '.$task['assigned_lname']) ?></td>
                            <td><?= htmlspecialchars($task['status']) ?></td>
                            <td><?= htmlspecialchars($task['due_date']) ?></td>
                            <td>
                                <a href="view_single_task.php?id=<?= $task['id'] ?>">View</a> |
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
        </div>
    </main>

</div>

</body>
</html>

<?php
/* Close connection only once at the very end */
$conn->close();
?>
