<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

// Get user ID from URL parameter
$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    header('Location: admin_dashboard.php');
    exit();
}

// Delete user
$delete_query = $conn->prepare("DELETE FROM users WHERE id = ?");
$delete_query->bind_param('i', $user_id);
$delete_query->execute();

header('Location: admin_dashboard.php');
exit();
