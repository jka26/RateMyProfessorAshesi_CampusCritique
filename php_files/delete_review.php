<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

// Get review ID from URL parameter
$review_id = $_GET['id'] ?? null;
if (!$review_id) {
    header('Location: admin_dashboard.php');
    exit();
}

// Delete review
$delete_query = $conn->prepare("DELETE FROM reviews WHERE review_id = ?");
$delete_query->bind_param('i', $review_id);
$delete_query->execute();

header('Location: admin_dashboard.php');
exit();
