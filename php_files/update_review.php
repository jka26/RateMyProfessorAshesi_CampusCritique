<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review_id = $_POST['review_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE reviews SET rating = ?, comment = ? WHERE review_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isii", $rating, $comment, $review_id, $user_id);

    if ($stmt->execute()) {
        header("Location: my_reviews.php?updated=1");
        exit();
    } else {
        echo "Error updating review.";
    }
}
?>
