<?php
/*$conn = new mysqli("localhost", "root", "", "dolphin_crm");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// password validation
if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $password)) {
    die("Password must contain:
        at least 8 chars,
        one number,
        one lowercase,
        one uppercase.");
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO USERS (firstname, lastname, email, password, role)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed, $role);

if ($stmt->execute()) {
    echo "User added successfully!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
*/
