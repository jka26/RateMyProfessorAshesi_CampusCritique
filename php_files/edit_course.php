<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

// Get the course ID from the URL
$course_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get updated course details from the form
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Update course in the database
    $sql = "UPDATE courses SET title='$title', description='$description' WHERE course_id='$course_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Course updated successfully!";
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . $conn->error;
    }
}

// Get the current course details
$course = $conn->query("SELECT * FROM courses WHERE course_id = '$course_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course | Admin Dashboard</title>
    <link rel="stylesheet" href="../public/styles/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h2>Edit Course</h2>
        <nav class="admin-nav">
            <a href="../php_files/logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-main">
        <form method="POST">
            <div class="form-group">
                <label for="title">Course Title:</label>
                <input type="text" id="title" name="title" value="<?= $course['title'] ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Course Description:</label>
                <textarea id="description" name="description" rows="4" required><?= $course['description'] ?></textarea>
            </div>
            <button type="submit" class="button">Update Course</button>
        </form>
    </main>
</body>
</html>
