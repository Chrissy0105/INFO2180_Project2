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

$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT id, title, firstname, lastname, email, company, type
        FROM Contacts";

if ($filter === 'sales') {
    $sql .= " WHERE type = 'sales'";
} elseif ($filter === 'support') {
    $sql .= " WHERE type = 'support'";
} elseif ($filter === 'mine') {
    $sql .= " WHERE assigned_to = " . (int)$_SESSION['user_id'];
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);

// Contacts
$contacts_result = $conn->query("SELECT id, title, firstname, lastname, email, company, type FROM Contacts ORDER BY created_at DESC");

// Tasks (admins only)
$tasks_result = null;
if ($role === 'administrator') {
    $tasks_result = $conn->query("
        SELECT t.*, 
               c.firstname AS contact_fname, c.lastname AS contact_lname, 
               u.firstname AS assigned_fname, u.lastname AS assigned_lname
        FROM Tasks t
        LEFT JOIN Contacts c ON t.contact_id=c.id
        LEFT JOIN USERS u ON t.assigned_to=u.id
        ORDER BY t.due_date ASC
    ");
}

// Cases (admins only)
$cases_result = null;
if ($role === 'administrator') {
    $cases_result = $conn->query("
        SELECT cs.*, c.firstname AS contact_fname, c.lastname AS contact_lname
        FROM Cases cs
        LEFT JOIN Contacts c ON cs.contact_id=c.id
        ORDER BY cs.created_at DESC
    ");
}

// Files (admins only)
$files_result = null;
if ($role === 'administrator') {
    $files_result = $conn->query("
        SELECT f.id, f.filename, f.path, f.uploaded_at, 
               c.firstname AS contact_fname, c.lastname AS contact_lname,
               u.firstname AS uploaded_fname, u.lastname AS uploaded_lname
        FROM Files f
        LEFT JOIN Contacts c ON f.contact_id = c.id
        LEFT JOIN USERS u ON f.uploaded_by = u.id
        ORDER BY f.uploaded_at DESC
    ");
}
?>

<section class="card">
            <div class="card-header">
                <div class="card-title">Contacts</div>
                <a href="new_contact.php" class="btn-primary" id="button" data-page="new_contact.php">+ Add Contact</a>
            </div>
	            <div class="filters">
                <a href="index.php?filter=all" id="allF" data-page="index.php?filter=all">All</a>
                <a href="index.php?filter=sales" id="salesF" data-page="index.php?filter=sales">Sales Leads</a>
                <a href="index.php?filter=support"id="supportF" data-page="index.php?filter=support">Support</a>
                <a href="index.php?filter=mine" id="mineF" data-page="index.php?filter=mine">Assigned to me</a>
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
                                    <a href="view_contact.php?id=<?= $row['id'] ?>" id="homeView" data-page="view_contact.php?id=<?= $row['id'] ?>">View</a>
                                    <?php if ($role === 'administrator'): ?>
                                        | <a href="edit_contact.php?id=<?= $row['id'] ?>" id="homeEdit" data-page="edit_contact.php?id=<?= $row['id'] ?>">Edit</a>
                                        | <a href="delete_contact.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this contact?')" class="homeDelete" data-page="delete_contact.php?id=<?= $row['id'] ?>">Delete</a>
                                        | <a href="new_task.php?contact_id=<?= $row['id'] ?> " id="homeTask" data-page="new_task.php?contact_id=<?= $row['id'] ?> ">Add Task</a>
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

<?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Tasks</div>
                <a href="new_task.php" class="btn-primary" id="taskBtn" data-page="new_task.php">+ Add Task</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th><th>Contact</th><th>Assigned To</th><th>Status</th><th>Due Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($tasks_result && $tasks_result->num_rows > 0): ?>
                        <?php while($task = $tasks_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= htmlspecialchars($task['contact_fname'].' '.$task['contact_lname']) ?></td>
                                <td><?= htmlspecialchars($task['assigned_fname'].' '.$task['assigned_lname']) ?></td>
                                <td><?= htmlspecialchars($task['status']) ?></td>
                                <td><?= htmlspecialchars($task['due_date']) ?></td>
                                <td>
                                    <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a> |
                                    <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No tasks found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>

        <!-- CASES -->
        <?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Cases</div>
                <a href="new_case.php" class="btn-primary" id="caseBtn" data-page="new_case.php">+ New Case</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th><th>Contact</th><th>Status</th><th>Created At</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($cases_result && $cases_result->num_rows > 0): ?>
                        <?php while($case = $cases_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($case['title']) ?></td>
                            <td><?= htmlspecialchars($case['contact_fname'].' '.$case['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($case['status']) ?></td>
                            <td><?= htmlspecialchars($case['created_at']) ?></td>
                            <td>
                                <a href="edit_case.php?id=<?= $case['id'] ?>">Edit</a> |
                                <a href="delete_case.php?id=<?= $case['id'] ?>" onclick="return confirm('Delete this case?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No cases found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>

        <!-- FILES -->
        <?php if ($role === 'administrator'): ?>
        <section class="card">
            <div class="card-header">
                <div class="card-title">Files</div>
                <a href="upload_file.php" class="btn-primary" id="upldBtn" data-page="upload_file.php">+ Upload File</a>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>File Name</th><th>Contact</th><th>Uploaded By</th><th>Uploaded At</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($files_result && $files_result->num_rows > 0): ?>
                        <?php while($file = $files_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($file['filename']) ?></td>
                            <td><?= htmlspecialchars($file['contact_fname'].' '.$file['contact_lname']) ?></td>
                            <td><?= htmlspecialchars($file['uploaded_fname'].' '.$file['uploaded_lname']) ?></td>
                            <td><?= htmlspecialchars($file['uploaded_at']) ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($file['path']) ?>" target="_blank">View</a> |
                                <a href="delete_file.php?id=<?= $file['id'] ?>" onclick="return confirm('Delete this file?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No files uploaded.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>


<?php $conn->close(); ?>
