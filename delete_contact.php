<?php
session_start();

/* Only administrators can delete contacts */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: view_contacts.php");
    exit();
}

$contact_id = (int)$_GET['id'];

/* Database connection */
$conn = new mysqli('localhost', 'root', '', 'dolphin_crm');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* Delete the contact */
$stmt = $conn->prepare("DELETE FROM Contacts WHERE id = ?");
$stmt->bind_param("i", $contact_id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: view_contacts.php?deleted=1");
    exit();
} else {
    $stmt->close();
    $conn->close();
    die("Error deleting contact.");
}
?>
