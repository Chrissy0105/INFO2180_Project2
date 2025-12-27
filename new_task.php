<?php
session_start();

/* Only logged-in admins can add tasks */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$feedback = "";

/* Fetch contacts for dropdown */
$contacts = [];
$contactResult = $conn->query("SELECT id, firstname, lastname FROM Contacts ORDER BY firstname");
while ($row = $contactResult->fetch_assoc()) {
    $contacts[] = $row;
}

/* Fetch users for Assigned To dropdown */
$users = [];
$userResult = $conn->query("SELECT id, firstname, lastname FROM USERS ORDER BY firstname");
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

/* Handle form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $contact_id  = $_POST['contact_id'] ?? '';
    $assigned_to = $_POST['assigned_to'] ?: null;
    $status      = $_POST['status'] ?? 'Pending';
    $due_date    = $_POST['due_date'] ?: null;
    $created_by  = $_SESSION['user_id'];

    /* Validation */
    if ($title === "" || $contact_id === "") {
        $feedback = "Title and Contact are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO Tasks (title, description, contact_id, assigned_to, status, due_date, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssisssi",
            $title,
            $description,
            $contact_id,
            $assigned_to,
            $status,
            $due_date,
            $created_by
        );

        if ($stmt->execute()) {
            $feedback = "Task added successfully.";
        } else {
            $feedback = "Error adding task.";
        }

        $stmt->close();
    }
}

?>

        <div class="card">
            <div class="card-title">New Task</div>

            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>

            <form method="POST" class="form-grid">

                <div class="form-field">
                    <label for="title">Task Title *</label>
                    <input id="title" type="text" name="title" required>
                </div>

                <div class="form-field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>
                </div>

                <div class="form-field">
                    <label for="contact_id">Contact *</label>
                    <select id="contact_id" name="contact_id" required>
                        <option value="">Select Contact</option>
                        <?php foreach ($contacts as $contact): ?>
                            <option value="<?= $contact['id'] ?>">
                                <?= htmlspecialchars($contact['firstname'] . ' ' . $contact['lastname']) ?>
                            </option>
                        <?php endforeach; ?>
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

                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="due_date">Due Date</label>
                    <input id="due_date" type="date" name="due_date">
                </div>

                <div class="form-field full-width">
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save Task</button>
                    </div>
                </div>

            </form>
        </div>

<?php $conn->close(); ?>
