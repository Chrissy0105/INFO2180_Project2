<?php
session_start();

/* Any logged-in user can edit a contact */
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

/* Fetch users for Assigned To dropdown */
$users = [];
$userResult = $conn->query("SELECT id, firstname, lastname FROM USERS ORDER BY firstname");
while ($row = $userResult->fetch_assoc()) {
    $users[] = $row;
}

/* Fetch the contact to edit */
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Contacts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $contact = $result->fetch_assoc();
    if (!$contact) {
        header("Location: view_contacts.php");
        exit();
    }
} else {
    header("Location: view_contacts.php");
    exit();
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

    /* Validation */
    if ($firstname === "" || $lastname === "" || $email === "") {
        $feedback = "First name, last name, and email are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid email address.";
    } elseif ($type !== "sales lead" && $type !== "support") {
        $feedback = "Invalid contact type selected.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE Contacts 
             SET title=?, firstname=?, lastname=?, email=?, telephone=?, company=?, type=?, assigned_to=?
             WHERE id=?"
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
            $id
        );
        if ($stmt->execute()) {
            $feedback = "Contact updated successfully.";
            // Refresh contact data
            $stmt2 = $conn->prepare("SELECT * FROM Contacts WHERE id=?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $contact = $stmt2->get_result()->fetch_assoc();
        } else {
            $feedback = "Error updating contact.";
        }
        $stmt->close();
    }
}
?>

        <div class="card">
            <div class="card-title">Edit Contact</div>

            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>

            <form method="POST" class="form-grid">

                <div class="form-field">
                    <label for="title">Title</label>
                    <input id="title" type="text" name="title" value="<?= htmlspecialchars($contact['title'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="firstname">First Name *</label>
                    <input id="firstname" type="text" name="firstname" value="<?= htmlspecialchars($contact['firstname'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="lastname">Last Name *</label>
                    <input id="lastname" type="text" name="lastname" value="<?= htmlspecialchars($contact['lastname'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="email">Email *</label>
                    <input id="email" type="email" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>" required>
                </div>

                <div class="form-field">
                    <label for="telephone">Telephone</label>
                    <input id="telephone" type="text" name="telephone" value="<?= htmlspecialchars($contact['telephone'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="company">Company</label>
                    <input id="company" type="text" name="company" value="<?= htmlspecialchars($contact['company'] ?? '') ?>">
                </div>

                <div class="form-field">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="">Select type</option>
                        <option value="sales lead" <?= ($contact['type'] ?? '') === 'sales lead' ? 'selected' : '' ?>>Sales Lead</option>
                        <option value="support" <?= ($contact['type'] ?? '') === 'support' ? 'selected' : '' ?>>Support</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="assigned_to">Assigned To</label>
                    <select id="assigned_to" name="assigned_to">
                        <option value="">Unassigned</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= ($contact['assigned_to'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-field full-width">
                    <div class="form-actions">
                        <button type="submit" id="edit_con_save">Save Changes</button>
                    </div>
                </div>

            </form>
        </div>


<?php $conn->close(); ?>
