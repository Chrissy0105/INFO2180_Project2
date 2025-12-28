<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') die("Access denied.");

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) die("Database connection failed.");

$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $contact_id = $_POST['contact_id'];
    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
    $target = $upload_dir . time() . "_" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO Files (contact_id, filename, path, uploaded_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $contact_id, $filename, $target, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        $feedback = "File uploaded successfully!";
    } else {
        $feedback = "Failed to upload file.";
    }
}

$contacts = $conn->query("SELECT id, firstname, lastname FROM Contacts ORDER BY firstname");
?>

        <div class="card">
            <div class="card-title">Upload File</div>
            <?php if ($feedback): ?>
                <p class="feedback"><?= htmlspecialchars($feedback) ?></p>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data">
                <label for="contact_id">Contact *</label>
                <select name="contact_id" id="contact_id" required>
                    <option value="">Select Contact</option>
                    <?php while ($c = $contacts->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['firstname'].' '.$c['lastname']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="file">File *</label>
                <input type="file" name="file" id="file" required>

                <div style="margin-top: 20px;">
                    <button type="submit">Upload</button>
                </div>
            </form>
        </div>

<?php $conn->close(); ?>
