<?php
session_start();

// Only admins can delete cases
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator') {
    die("Access denied.");
}

$conn = new mysqli("localhost", "root", "Jamaicakl#1", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed.");
}

if (isset($_GET['id'])) {
    $case_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Cases WHERE id = ?");
    $stmt->bind_param("i", $case_id);

    if ($stmt->execute()) {
        $stmt->close();
        // Redirect back to cases list
        header("Location: view_cases.php");
        exit();
    } else {
        echo "Error deleting case: " . $stmt->error;
    }
} else {
    echo "No case specified.";
}

$conn->close();
?>
