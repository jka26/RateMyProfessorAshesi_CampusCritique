<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to view your profile.";
    header("Location: ../home.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT name, email, created_at FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../public/styles/profile.css">
</head>
<body>

<div class="profile-container">
    <h1>👤 Your Profile</h1>
    <div class="profile-card">
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Member Since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
    </div>

    <div class="actions">
        <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

</body>
</html>
