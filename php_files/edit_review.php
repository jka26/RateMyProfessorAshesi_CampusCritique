<?php
session_start();
require_once '../config/db.php';

if (!isset($_GET['review_id'])) {
    header('Location: my_reviews.php');
    exit();
}

$review_id = $_GET['review_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM reviews WHERE review_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $review_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Review not found or unauthorized access.";
    exit();
}

$review = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Review</title>
    <link rel="stylesheet" href="../public/styles/edit_review.css">
</head>
<body>
    <h2>Edit Your Review</h2>
    <form action="update_review.php" method="POST">
        <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">

        <label for="rating">Rating (1 to 5):</label>
        <input type="number" name="rating" min="1" max="5" value="<?= $review['rating'] ?>" required>

        <label for="comment">Comment:</label>
        <textarea name="comment" rows="5" required><?= htmlspecialchars($review['comment']) ?></textarea>

        <button type="submit">Update Review</button>
        <a href="my_reviews.php">Cancel</a>
    </form>
</body>
</html>
