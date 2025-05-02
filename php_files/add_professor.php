<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get professor details from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    
    // Insert professor into the database
    $sql = "INSERT INTO professors (name, bio, department) VALUES ('$name', '$bio', '$department')";
    if ($conn->query($sql) === TRUE) {
        echo "New professor added successfully!";
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Professor | Admin Dashboard</title>
    <link rel="stylesheet" href="../public/styles/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h2>Add Professor</h2>
        <nav class="admin-nav">
            <a href="../php_files/logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-main">
        <form method="POST">
            <div class="form-group">
                <label for="name">Professor Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="bio">Biography:</label>
                <textarea id="bio" name="bio" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <input type="text" id="department" name="department" required>
            </div>
            <button type="submit" class="button">Add Professor</button>
        </form>
    </main>
</body>
</html>
