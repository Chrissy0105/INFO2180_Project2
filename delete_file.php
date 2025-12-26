<?php
session_start();

$conn = new mysqli("localhost", "root", "Jamaicakl#1", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed");
}


// Only admins can delete files
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    die("Access denied. Administrators only.");
}

if (!isset($_GET['id'])) {
    die("File ID not specified.");
}

$file_id = (int)$_GET['id'];

// Get the file path from the database
$stmt = $conn->prepare("SELECT path FROM Files WHERE id = ?");
$stmt->bind_param("i", $file_id);
$stmt->execute();
$stmt->bind_result($file_path);
if ($stmt->fetch()) {
    $stmt->close();

    // Delete the physical file if it exists
    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // Delete the database record
    $del_stmt = $conn->prepare("DELETE FROM Files WHERE id = ?");
    $del_stmt->bind_param("i", $file_id);
    if ($del_stmt->execute()) {
        $del_stmt->close();
        header("Location: dashboard.php"); // redirect back to dashboard
        exit();
    } else {
        die("Failed to delete file from database.");
    }

} else {
    $stmt->close();
    die("File not found.");
}

$conn->close();
?>
