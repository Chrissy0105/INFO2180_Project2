<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: view_contacts.php");
    exit();
}

// Validate input
if (
    !isset($_POST['contact_id'], $_POST['comment']) ||
    !is_numeric($_POST['contact_id']) ||
    trim($_POST['comment']) === ''
) {
    header("Location: view_contacts.php");
    exit();
}

$conn = new mysqli("localhost", "root", "Jamaicakl#1", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed");
}

$contact_id = (int) $_POST['contact_id'];
$user_id = (int) $_SESSION['user_id'];
$comment = $conn->real_escape_string(trim($_POST['comment']));

/* Insert note */
$conn->query("
    INSERT INTO Notes (contact_id, comment, created_by)
    VALUES ($contact_id, '$comment', $user_id)
");

/* Update contact updated_at */
$conn->query("
    UPDATE Contacts
    SET updated_at = CURRENT_TIMESTAMP
    WHERE id = $contact_id
");

// Redirect back to the contact page
header("Location: view_contact.php?id=$contact_id");
exit();
?>
