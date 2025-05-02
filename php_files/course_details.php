<?php
session_start();
require_once('../config/db.php');

// Check if 'id' is set in the query string
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Query to fetch the course details based on course_id
    $query = "SELECT * FROM courses WHERE course_id = $course_id";
    $result = mysqli_query($conn, $query);

    // If a course is found, fetch it
    if ($course = mysqli_fetch_assoc($result)) {
        // Course details are available in $course
    } else {
        echo "Course not found.";
        exit;
    }
} else {
    echo "Invalid course ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Details | Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/course_details.css">
</head>
<body>
    <div class="course-details-container">
        <h1>Course Details</h1>
        <?php if ($course): ?>
            <h2><?php echo htmlspecialchars($course['code']) . " - " . htmlspecialchars($course['title']); ?></h2>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
            <p><strong>Created At:</strong> <?php echo htmlspecialchars($course['created_at']); ?></p>
        <?php else: ?>
            <p>Course not found.</p>
        <?php endif; ?>
        <a href="courses.php" class="back-btn">Back to Courses</a>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-list">
        <!-- <h3>Reviews</h3> -->
        <?php
        // Fetch reviews for the course
        $target_type = 'course';
        $target_id = $course['course_id'];

        $reviewsQuery = $conn->prepare("SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.target_type = ? AND r.target_id = ? ORDER BY r.created_at DESC");
        $reviewsQuery->bind_param("si", $target_type, $target_id);
        $reviewsQuery->execute();
        $reviewsResult = $reviewsQuery->get_result();

        while ($review = $reviewsResult->fetch_assoc()) {
            echo "<div class='review-card'>";
            echo "<p><strong>" . htmlspecialchars($review['username']) . "</strong> rated this " . $review['rating'] . " stars.</p>";
            echo "<p>" . htmlspecialchars($review['comment']) . "</p>";
            echo "<p><em>Reviewed on: " . $review['created_at'] . "</em></p>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Add review form below the reviews section -->
    <div class="review-form-container">
        <h3>Submit Your Review</h3>
        <form method="POST" action="submit_review.php">
            <input type="hidden" name="target_type" value="course">
            <input type="hidden" name="target_id" value="<?php echo $course['course_id']; ?>">

            <label for="rating">Rating: </label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
            </div>

            <label for="comment">Your Review: </label>
            <textarea name="comment" id="comment" rows="5" required></textarea>

            <button type="submit">Submit Review</button>
        </form>
    </div>
</body>
</html>
