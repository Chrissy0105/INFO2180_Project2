<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "dolphin_crm");
if ($conn->connect_error) {
    die("Database connection failed");
}

$sql = "
    SELECT 
        C.id,
        C.firstname,
        C.lastname,
        C.email,
        C.company,
        C.type,
        U.firstname AS assigned_first,
        U.lastname AS assigned_last
    FROM Contacts C
    LEFT JOIN USERS U ON C.assigned_to = U.id
    ORDER BY C.created_at DESC
";

$result = $conn->query($sql);
?>

<div class="card">
    <div class="card-title">Contacts</div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Type</th>
                    <th>Assigned To</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): 
                $type_class = strtolower(str_replace(' ', '-', $row['type']));
            ?>
                <tr>
                    <td>
                        <a href="view_contact.php?id=<?= $row['id'] ?>">
                            <?= htmlspecialchars($row['firstname'].' '.$row['lastname']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['company']) ?></td>
                    <td>
                        <span class="badge <?= $type_class ?>">
                            <?= htmlspecialchars($row['type']) ?>
                        </span>
                    </td>
                    <td>
                        <?= $row['assigned_first']
                            ? htmlspecialchars($row['assigned_first'].' '.$row['assigned_last'])
                            : 'Unassigned'; ?>
                    </td>
                    <td>
                        <a href="view_contact.php?id=<?= $row['id'] ?>" id="return_to_contact_from_view_contact">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No contacts found.</p>
    <?php endif; ?>

</div>

<?php $conn->close(); ?>
