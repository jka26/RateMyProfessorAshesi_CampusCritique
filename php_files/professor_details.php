<?php
require_once '../config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $professor_id = $_GET['id'];

    // Fetch professor info
    $stmt = $conn->prepare("SELECT * FROM professors WHERE id = ?");
    $stmt->bind_param("i", $professor_id);
    $stmt->execute();
    $professor_result = $stmt->get_result();

    if ($professor_result->num_rows > 0) {
        $professor = $professor_result->fetch_assoc();

        // Fetch reviews
        $reviewsQuery = $conn->prepare("
            SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.user_id 
            WHERE r.target_type = 'professor' AND r.target_id = ?
            ORDER BY r.created_at DESC
        ");
        $reviewsQuery->bind_param("i", $professor_id);
        $reviewsQuery->execute();
        $reviewsResult = $reviewsQuery->get_result();
    } else {
        header("Location: professors.php");
        exit();
    }
} else {
    header("Location: professors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($professor['name']) ?> - Professor Details</title>
    <link rel="stylesheet" href="../public/styles/professor_details.css">
</head>
<body>
    <div class="container">
        <section class="professor-info">
            <h1><?= htmlspecialchars($professor['name']) ?></h1>
            <p><strong>Department:</strong> <?= htmlspecialchars($professor['department']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($professor['email']) ?></p>
        </section>

        <section class="reviews-section">
            <h2>Reviews</h2>
            <?php if ($reviewsResult->num_rows > 0): ?>
                <?php while ($review = $reviewsResult->fetch_assoc()): ?>
                    <article class="review-card">
                        <header>
                            <strong><?= htmlspecialchars($review['username']) ?></strong> 
                            <span class="rating">⭐ <?= $review['rating'] ?>/5</span>
                        </header>
                        <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        <footer><small>Reviewed on <?= htmlspecialchars($review['created_at']) ?></small></footer>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews yet for this professor.</p>
            <?php endif; ?>
        </section>

        <section class="submit-review">
            <h2>Leave a Review</h2>
            <form method="POST" action="submit_review.php">
                <input type="hidden" name="target_type" value="professor">
                <input type="hidden" name="target_id" value="<?= $professor['id']; ?>">

                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="" disabled selected>Select</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>

                <label for="comment">Your Review:</label>
                <textarea name="comment" id="comment" rows="4" required></textarea>

                <button type="submit">Submit Review</button>
            </form>
        </section>

        <div class="back-link">
            <a href="professors.php">← Back to Professors List</a>
        </div>
    </div>
</body>
</html>
