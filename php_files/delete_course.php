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

// Delete the course from the database
$sql = "DELETE FROM courses WHERE course_id = '$course_id'";
if ($conn->query($sql) === TRUE) {
    echo "Course deleted successfully!";
    header('Location: admin_dashboard.php');
} else {
    echo "Error: " . $conn->error;
}
?>
