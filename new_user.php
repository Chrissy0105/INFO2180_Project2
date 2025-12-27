<?php
session_start();

// Only allow logged-in Admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrator')  die("Access denied.");

$feedback = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($firstname === "" || $lastname === "" || $email === "" || $password === "" || $role === "") {
        $feedback = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "Please enter a valid email address.";
    } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $password)) {
        $feedback = "Password must be at least 8 characters and include a number, a lowercase and an uppercase letter.";
    } elseif ($role !== "administrator" && $role !== "user") {
        $feedback = "Invalid role selected.";
    } else {
        $conn = new mysqli("localhost", "root", "", "dolphin_crm");

        if ($conn->connect_error) {
            $feedback = "Database connection failed.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO USERS (firstname, lastname, email, password, role)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $hashedPassword, $role);

            if ($stmt->execute()) {
                $feedback = "User added successfully.";
				header("Location: index2.html");
            } else {
                $feedback = "Error adding user: " . htmlspecialchars($conn->error);
            }

            $stmt->close();
            $conn->close();
        }
    }
}
?>
            <div class="card">
                <div class="card-title">New User</div>

                <?php if ($feedback): ?>
                    <p class="feedback"><?php echo htmlspecialchars($feedback); ?></p>
                <?php endif; ?>

                <form method="POST" action="new_user.php" class="form-grid">

                    <div class="form-field">
                        <label for="firstname">First Name</label>
                        <input id="firstname" type="text" name="firstname" required>
                    </div>

                    <div class="form-field">
                        <label for="lastname">Last Name</label>
                        <input id="lastname" type="text" name="lastname" required>
                    </div>

                    <div class="form-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required>
                    </div>

                    <div class="form-field">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" required>
                            <span class="toggle-password" onclick="togglePassword()">👁</span>
                        </div>
                    </div>

                    <div class="form-field full-width">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="">Select role</option>
                            <option value="administrator">Admin</option>
                            <option value="user">Member</option>
                        </select>
                    </div>

                    <div class="form-field full-width">
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Save</button>
                        </div>
                    </div>

                </form>
            </div>
    <script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        pwd.type = pwd.type === "password" ? "text" : "password";
    }
    </script>