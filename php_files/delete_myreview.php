<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the user owns the review
    $stmt = $conn->prepare("DELETE FROM reviews WHERE review_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $review_id, $user_id);
    $stmt->execute();

    header("Location: my_reviews.php?deleted=true");
    exit();
} else {
    header("Location: my_reviews.php");
    exit();
}
