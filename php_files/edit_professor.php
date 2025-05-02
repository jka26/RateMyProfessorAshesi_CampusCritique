<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

$professor_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated professor details from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    
    // Update professor in the database
    $sql = "UPDATE professors SET name='$name', email='$email', department='$department' WHERE id='$professor_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Professor updated successfully!";
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get the current professor details
$professor = $conn->query("SELECT * FROM professors WHERE id = '$professor_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Professor | Admin Dashboard</title>
    <link rel="stylesheet" href="../public/styles/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h2>Edit Professor</h2>
        <nav class="admin-nav">
            <a href="../php_files/logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-main">
        <form method="POST">
            <div class="form-group">
                <label for="name">Professor Name:</label>
                <input type="text" id="name" name="name" value="<?= $professor['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <textarea id="email" name="email" rows="4" required><?= $professor['email'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" id="department" name="department" value="<?= $professor['department'] ?>" required>
            </div>
            <button type="submit" class="button">Update Professor</button>
        </form>
    </main>
</body>
</html>
