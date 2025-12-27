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


<?php $conn->close(); ?>
