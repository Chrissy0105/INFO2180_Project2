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
if ($conn->connect_error) {
    die("Database connection failed.");
}

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
?>

<div class="card">

            <div class="card-header">
                <div class="card-title">Dashboard</div>
                <a href="new_contact.php" class="btn-primary" id= "addContactFromDash">+ Add Contact</a>
            </div>

            <div class="filters">
                <a href="index.php?filter=all" id="allF">All</a>
                <a href="index.php?filter=sales" id="salesF">Sales Leads</a>
                <a href="index.php?filter=support" id="supportF">Support</a>
                <a href="index.php?filter=mine" id="mineF">Assigned to me</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title'].' '.$row['firstname'].' '.$row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['company']) ?></td>
                            <td>
                                <?php
                                    $typeLabel = $row['type'] === 'sales' ? 'Sales Lead' : 'Support';
                                    $typeClass = $row['type'] === 'sales' ? 'sales-lead' : 'support';
                                ?>
                                <span class="badge <?= $typeClass ?>">
                                    <?= $typeLabel ?>
                                </span>
                            </td>

                            <td>
                                <a href="view_contact.php?id=<?= $row['id'] ?>" class="homeView">View</a>
                            	<?php if ($role === 'administrator'): ?>
                                        | <a href="edit_contact.php?id=<?= $row['id'] ?>" class="homeEdit">Edit</a>
                                        | <a href="delete_contact.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this contact?')" class="homeDelete">Delete</a>
                                        | <a href="new_task.php?contact_id=<?= $row['id'] ?>" class="homeTask">Add Task</a>
                                    <?php endif; ?>
							</td>
							
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No contacts found.</td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>

        </div>


<?php $conn->close(); ?>
