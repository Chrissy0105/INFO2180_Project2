<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body class="dashboard-body">

    <div class="sidebar">
        <div class="sidebar-title">Dolphin CRM</div>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="new_contact.php">New Contact</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Welcome, <?php echo htmlspecialchars("$firstname $lastname"); ?></h1>
        <p>Role: <?php echo htmlspecialchars($role); ?></p>
        <p>This is your dashboard. We’ll later show contacts here.</p>
    </div>

</body>

</html>