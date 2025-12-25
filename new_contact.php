<?php
session_start();

/*Any logged-in user can add a contact */ 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* Database connection */
$conn = new mysqli('localhost', 'root', '', 'dolphin_crm');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$feedback = "";

/* Fetches users for Assigned to drop down */
$users = [];
$userResult = $conn->query("SELECT id, firstname, lastname FROM USERS ORDER BY firstname");
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

/* Handle form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title       = trim($_POST['title'] ?? '');
    $firstname   = trim($_POST['firstname'] ?? '');
    $lastname    = trim($_POST['lastname'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $telephone   = trim($_POST['telephone'] ?? '');
    $company     = trim($_POST['company'] ?? '');
    $type        = $_POST['type'] ?? '';
    $assigned_to = $_POST['assigned_to'] ?: null;
    $created_by = $_SESSION['user_id'];

    /* Validation */
    if ($firstname === "" || $lastname === "" || $email === "") {
        $feedback = "First name, last name, and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid email address.";
    } elseif ($type !== "sales lead" && $type !== "support") {
        $feedback = "Invalid contact type selected.";
    } else {

        $stmt = $conn->prepare(
            "INSERT INTO Contacts
            (Title, firstname, lastname, email, telephone, company, type, assigned_to, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssssii",
            $title,
            $firstname,
            $lastname,
            $email,
            $telephone,
            $company,
            $type,
            $assigned_to,
            $created_by
        );

        if ($stmt->execute()) {
            $feedback = "Contact added successfully.";
        } else {
            $feedback = "Error adding contact.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dolphin CRM – New Contact</title>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body class="app-body">

<div class="top-nav">Dolphin CRM</div>

<div class="app-wrapper">

    <aside class="sidebar">
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="new_contact.php" class="active">New Contact</a></li>
            <li><a href="new_user.php">New User</a></li>
            <li><a href="view_users.php">Users</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="card">
            <div class="card-title">New Contact</div>

            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>

            <form method="POST" class="form-grid">

                <div class="form-field">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title">
                </div>

                <div class="form-field">
                    <label for="firstname">First Name *</label>
                    <input id="firstname" type="text" name="firstname" required>
                </div>

                <div class="form-field">
                    <label for="lastname">Last Name *</label>
                    <input id="lastname" type="text" name="lastname" required>
                </div>

                <div class="form-field">
                    <label for="email">Email *</label>
                    <input id="email" type="email" name="email" required>
                </div>

                <div class="form-field">
                    <label for="telephone">Telephone</label>
                    <input id="telephone" type="text" name="telephone">
                </div>

                <div class="form-field">
                    <label for="company">Company</label>
                    <input id="company" type="text" name="company">
                </div>

                <div class="form-field">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="">Select type</option>
                        <option value="sales lead">Sales Lead</option>
                        <option value="support">Support</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="assigned_to">Assigned To</label>
                    <select id="assigned_to" name="assigned_to">
                        <option value="">Unassigned</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field full-width">
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </main>

</div>

</body>
</html>

<?php $conn->close(); ?>
