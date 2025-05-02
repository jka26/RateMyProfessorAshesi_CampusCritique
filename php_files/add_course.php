<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get course details from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Insert course into the database
    $sql = "INSERT INTO courses (title, description) VALUES ('$title', '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "New course added successfully!";
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
    <title>Add Course | Admin Dashboard</title>
    <link rel="stylesheet" href="../public/styles/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h2>Add Course</h2>
        <nav class="admin-nav">
            <a href="../php_files/logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-main">
        <form method="POST">
            <div class="form-group">
                <label for="title">Course Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Course Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <button type="submit" class="button">Add Course</button>
        </form>
    </main>
</body>
</html>
