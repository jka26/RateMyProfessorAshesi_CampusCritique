<?php
// admin_users.php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../php_files/dashboard.php");
    exit();
}

// Handle role promotion
if (isset($_GET['promote'])) {
    $userId = intval($_GET['promote']);
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    header("Location: admin_users.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    header("Location: admin_users.php");
    exit();
}

$result = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link rel="stylesheet" href="../public/styles/admin_styles.css">
</head>
<body>
    <header class="admin-header">
        <h2>Admin - Manage Users</h2>
        <nav>
            <a href="admin_dashboard.php">← Back to Dashboard</a>
        </nav>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['role'] ?></td>
                        <td>
                            <?php if ($row['role'] !== 'admin'): ?>
                                <a href="?promote=<?= $row['user_id'] }">Promote</a>
                            <?php endif; ?>
                            <a href="?delete=<?= $row['user_id'] }" onclick="return confirm('Delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
