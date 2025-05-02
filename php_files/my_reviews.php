<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM reviews WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Reviews - Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/my_reviews.css">
</head>
<body>
    <header>
        <h2>My Reviews</h2>
        <a href="dashboard.php">← Back to Dashboard</a>
    </header>

    <main class="reviews-container">
        <?php if (isset($_GET['updated'])): ?>
            <p class="success-msg">✅ Review updated successfully!</p>
        <?php elseif (isset($_GET['deleted'])): ?>
            <p class="success-msg">🗑️ Review deleted successfully!</p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <p><strong>Rating:</strong> <?= htmlspecialchars($row['rating']) ?>/5</p>
                    <p><strong>Comment:</strong> <?= htmlspecialchars($row['comment']) ?></p>
                    <p><strong>For:</strong> <?= ucfirst($row['target_type']) ?> (ID: <?= $row['target_id'] ?>)</p>
                    <p><small>Submitted on <?= date('F j, Y', strtotime($row['created_at'])) ?></small></p>
                    <a href="edit_review.php?review_id=<?= $row['review_id'] ?>" class="edit-btn">✏️ Edit</a>
                    <form action="delete_myreview.php" method="POST" style="display:inline;">
                        <input type="hidden" name="review_id" value="<?= $row['review_id'] ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this review?')">🗑️ Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You haven't submitted any reviews yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>
