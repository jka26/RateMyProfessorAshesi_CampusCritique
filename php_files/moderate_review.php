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

// Fetch review data
$review_query = $conn->prepare("SELECT review_id, user_id, rating, comment FROM reviews WHERE review_id = ?");
$review_query->bind_param('i', $review_id);
$review_query->execute();
$review_result = $review_query->get_result();
$review = $review_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update review
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    $update_query = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE review_id = ?");
    $update_query->bind_param('isi', $rating, $comment, $review_id);
    $update_query->execute();

    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
</head>
<body>
    <h2>Edit Review</h2>
    <form method="POST">
        <label for="rating">Rating (1-5)</label>
        <input type="number" name="rating" id="rating" value="<?= $review['rating'] ?>" min="1" max="5">
        <br>
        <label for="comment">Comment</label>
        <textarea name="comment" id="comment"><?= $review['comment'] ?></textarea>
        <br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
