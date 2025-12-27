<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid contact ID");
}

$contact_id = (int) $_GET['id'];

$stmt = $conn->prepare("
    SELECT 
        C.id,
        C.title,
        C.firstname,
        C.lastname,
        C.email,
        C.telephone,
        C.company,
        C.type,
        C.created_at,
        U.firstname AS assigned_first,
        U.lastname AS assigned_last
    FROM Contacts C
    LEFT JOIN USERS U ON C.assigned_to = U.id
    WHERE C.id = ?
    LIMIT 1
");
$stmt->bind_param("i", $contact_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Contact not found");
}

$contact = $result->fetch_assoc();
$stmt->close();

/* Normalize type for CSS badge */
$type_class = strtolower(str_replace(' ', '-', $contact['type']));

/* Fetch notes */
$notes_stmt = $conn->prepare("
    SELECT 
        N.comment,
        N.created_at,
        U.firstname,
        U.lastname
    FROM Notes N
    JOIN USERS U ON N.created_by = U.id
    WHERE N.contact_id = ?
    ORDER BY N.created_at DESC
");
$notes_stmt->bind_param("i", $contact_id);
$notes_stmt->execute();
$notes_result = $notes_stmt->get_result();
?>
<div class="card">

    <!-- CONTACT HEADER -->
    <div class="card-header">
        <h2>
            <?= htmlspecialchars($contact['title'].' '.$contact['firstname'].' '.$contact['lastname']) ?>
        </h2>
        <span class="badge <?= $type_class ?>">
            <?= htmlspecialchars($contact['type']) ?>
        </span>
    </div>

    <p><strong>Email:</strong> <?= htmlspecialchars($contact['email']) ?></p>
    <p><strong>Telephone:</strong> <?= htmlspecialchars($contact['telephone']) ?></p>
    <p><strong>Company:</strong> <?= htmlspecialchars($contact['company']) ?></p>
    <p><strong>Assigned To:</strong>
        <?= $contact['assigned_first']
            ? htmlspecialchars($contact['assigned_first'].' '.$contact['assigned_last'])
            : 'Unassigned'; ?>
    </p>
    <p><strong>Created:</strong>
        <?= date("F j, Y", strtotime($contact['created_at'])) ?>
    </p>

    <hr>

    <!-- NOTES -->
    <div class="card-title">Notes</div>

    <?php if ($notes_result->num_rows > 0): ?>
        <?php while ($note = $notes_result->fetch_assoc()): ?>
            <div class="note">
                <p class="note-meta">
                    <strong><?= htmlspecialchars($note['firstname'].' '.$note['lastname']) ?></strong>
                    <span><?= date("M d, Y H:i", strtotime($note['created_at'])) ?></span>
                </p>
                <p><?= nl2br(htmlspecialchars($note['comment'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No notes for this contact.</p>
    <?php endif; ?>

    <!-- ADD NOTE -->
    <form method="POST" action="add_note.php">
        <input type="hidden" name="contact_id" value="<?= $contact_id ?>">
        <label for="comment">Add a Note</label>
        <textarea name="comment" id="comment" required></textarea>
        <br>
        <button type="submit">Add Note</button>
    </form>

    <br>
    <a href="view_contacts.php" id="return_to_contact_from_view_contact">&larr; Back to Contacts</a>

</div>

<?php
$notes_stmt->close();
$conn->close();
?>
