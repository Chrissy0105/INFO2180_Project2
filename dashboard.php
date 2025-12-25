<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$firstname = $_SESSION['firstname'] ?? '';
$lastname  = $_SESSION['lastname'] ?? '';
$role      = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body class="app-body">

    <!-- TOP NAV (required by CSS) -->
    <div class="top-nav">
        Dolphin CRM
    </div>

    <!-- WRAPPER (required for flex layout) -->
    <div class="app-wrapper">

        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php">Home</a></li>
                <li><a href="new_user.php">New Contact</a></li>
                <li><a href="view_users.php">Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars("$firstname $lastname"); ?></h1>
            <p>Role: <?php echo htmlspecialchars($role); ?></p>
            <p>This is your dashboard. We’ll later show contacts here.</p>
        </div>

    </div>

</body>
</html>
