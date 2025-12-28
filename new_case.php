<?php
session_start();

/* Access control: only logged-in administrators */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] !== 'administrator') {
    die("Access denied. Administrators only.");
}

/* Database connection */
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

/* Handle form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'Open';
    $contact_id = $_POST['contact_id'] ?? null;
    $created_by = $_SESSION['user_id'];

    if ($title === "" || !$contact_id) {
        $feedback = "Title and Contact are required.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO Cases (contact_id, title, description, status, created_by)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("isssi", $contact_id, $title, $description, $status, $created_by);
        if ($stmt->execute()) {
            $feedback = "Case added successfully.";
        } else {
            $feedback = "Error adding case.";
        }
        $stmt->close();
    }
}

?>

        <div class="card">
            <div class="card-title">New Case</div>

            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>

            <form method="POST">

                <label for="contact_id">Contact *</label>
                <select id="contact_id" name="contact_id" required>
                    <option value="">Select Contact</option>
                    <?php foreach ($contacts as $contact): ?>
                        <option value="<?= $contact['id'] ?>">
                            <?= htmlspecialchars($contact['firstname'].' '.$contact['lastname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="title">Title *</label>
                <input id="title" type="text" name="title" required>

                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>

                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Open">Open</option>
                    <option value="Pending">Pending</option>
                    <option value="Closed">Closed</option>
                </select>

                <div style="margin-top: 20px;">
                    <button type="submit" id="_case">Save Case</button>
                </div>

            </form>
        </div>

<?php $conn->close(); ?>
