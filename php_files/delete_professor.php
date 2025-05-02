<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

// Get the professor ID from the URL
$professor_id = $_GET['id'];

// Delete the professor from the database
$sql = "DELETE FROM professors WHERE id = '$professor_id'";
if ($conn->query($sql) === TRUE) {
    echo "Professor deleted successfully!";
    header('Location: admin_dashboard.php');
} else {
    echo "Error: " . $conn->error;
}
?>
