<?php
session_start();

// Only admins can edit cases
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$case_id = $_GET['id'] ?? null;
if (!$case_id) {
    die("No case specified.");
}

// Fetch case data
$stmt = $conn->prepare("SELECT * FROM Cases WHERE id = ?");
$stmt->bind_param("i", $case_id);
$stmt->execute();
$case = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch contacts for dropdown
$contacts = [];
$contactResult = $conn->query("SELECT id, firstname, lastname FROM Contacts ORDER BY firstname");
while ($row = $contactResult->fetch_assoc()) {
    $contacts[] = $row;
}

$feedback = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Open';
    $contact_id = $_POST['contact_id'] ?? null;

    if ($title === "" || !$contact_id) {
        $feedback = "Title and Contact are required.";
    } else {
        $stmt = $conn->prepare("UPDATE Cases SET title=?, description=?, status=?, contact_id=? WHERE id=?");
        $stmt->bind_param("sssii", $title, $description, $status, $contact_id, $case_id);
        if ($stmt->execute()) {
            // Redirect back to cases list
            header("Location: view_cases.php");
            exit();
        } else {
            $feedback = "Error updating case: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – Edit Case</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body class="app-body">

<div class="top-nav">Dolphin CRM</div>

<div class="app-wrapper">

    <aside class="sidebar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="new_case.php">New Case</a></li>
            <li><a href="view_cases.php" class="active">Cases</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="card">
            <div class="card-title">Edit Case</div>

            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="contact_id">Contact *</label>
                <select id="contact_id" name="contact_id" required>
                    <option value="">Select Contact</option>
                    <?php foreach ($contacts as $contact): ?>
                        <option value="<?= $contact['id'] ?>" <?= $case['contact_id']==$contact['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($contact['firstname'].' '.$contact['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="title">Title *</label>
                <input id="title" type="text" name="title" value="<?= htmlspecialchars($case['title']) ?>" required>

                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($case['description']) ?></textarea>

                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Open" <?= $case['status']=='Open' ? 'selected' : '' ?>>Open</option>
                    <option value="Pending" <?= $case['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Closed" <?= $case['status']=='Closed' ? 'selected' : '' ?>>Closed</option>
                </select>

                <div style="margin-top:20px;">
                    <button type="submit">Update Case</button>
                </div>
            </form>
        </div>
    </main>
</div>

</body>
</html>

<?php $conn->close(); ?>
