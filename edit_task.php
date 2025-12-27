<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    die("Access denied.");
}

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid task ID.");

$task = $conn->query("SELECT * FROM Tasks WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $assigned_to = $_POST['assigned_to'] ?: null;
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE Tasks SET title=?, description=?, assigned_to=?, status=?, due_date=? WHERE id=?");
    $stmt->bind_param("ssisii", $title, $description, $assigned_to, $status, $due_date, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: view_task.php");
    exit();
}

$contacts = $conn->query("SELECT id, firstname, lastname FROM Contacts");
$users = $conn->query("SELECT id, firstname, lastname FROM USERS");
?>

<h2>Edit Task</h2>
<form method="post">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br>

    <label>Description:</label>
    <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br>

    <label>Assign To:</label>
    <select name="assigned_to">
        <option value="">--None--</option>
        <?php while ($u = $users->fetch_assoc()): ?>
            <option value="<?= $u['id'] ?>" <?= $task['assigned_to']==$u['id']?'selected':'' ?>>
                <?= htmlspecialchars($u['firstname'].' '.$u['lastname']) ?>
            </option>
        <?php endwhile; ?>
    </select><br>

    <label>Status:</label>
    <select name="status">
        <option value="Pending" <?= $task['status']=='Pending'?'selected':'' ?>>Pending</option>
        <option value="In Progress" <?= $task['status']=='In Progress'?'selected':'' ?>>In Progress</option>
        <option value="Completed" <?= $task['status']=='Completed'?'selected':'' ?>>Completed</option>
    </select><br>

    <label>Due Date:</label>
    <input type="date" name="due_date" value="<?= $task['due_date'] ?>"><br><br>

    <button type="submit">Update Task</button>
</form>

<?php $conn->close(); ?>
