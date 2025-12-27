<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$firstname = $_SESSION['firstname'] ?? '';
$lastname  = $_SESSION['lastname'] ?? '';
$role      = $_SESSION['role'] ?? '';

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) die("Database connection failed.");

// Contacts
$contacts_result = $conn->query("SELECT id, title, firstname, lastname, email, company, type FROM Contacts ORDER BY created_at DESC");
?>

<section class="card">
            <div class="card-header">
                <div class="card-title">Contacts</div>
                <a href="new_contact.php" class="btn-primary">+ Add Contact</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th><th>Email</th><th>Company</th><th>Type</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contacts_result && $contacts_result->num_rows > 0): ?>
                        <?php while ($row = $contacts_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title'].' '.$row['firstname'].' '.$row['lastname']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['company']) ?></td>
                                <td>
                                    <span class="badge <?= $row['type'] === 'Sales Lead' ? 'sales-lead' : 'support' ?>">
                                        <?= htmlspecialchars($row['type']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="view_contact.php?id=<?= $row['id'] ?>">View</a>
                                    <?php if ($role === 'administrator'): ?>
                                        | <a href="edit_contact.php?id=<?= $row['id'] ?>">Edit</a>
                                        | <a href="delete_contact.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this contact?')">Delete</a>
                                        | <a href="new_task.php?contact_id=<?= $row['id'] ?>">Add Task</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No contacts found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
 </section>