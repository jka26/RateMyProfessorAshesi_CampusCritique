<?php
session_start();
require_once('../config/db.php');

// Fetch departments
$departments_query = "SELECT DISTINCT department FROM professors ORDER BY department ASC";
$departments_result = $conn->query($departments_query);

$reviewSuccess = $_SESSION['review_success'] ?? false;
unset($_SESSION['review_success']);

// Fetch courses
$courses_query = "SELECT code, title FROM courses ORDER BY code ASC";
$courses_result = $conn->query($courses_query);
$review_type = $_GET['review_type'] ?? 'professor';
$department = $_GET['department'] ?? '';
$course = $_GET['course'] ?? '';

// Build query
$query = "SELECT r.*, 
                 u.name AS reviewer_name, 
                 p.name AS professor_name,
                 p.department AS department, 
                 c.code AS course_code,
                 c.title AS course_title,
                 (SELECT COUNT(*) FROM review_votes WHERE review_id = r.review_id AND vote_type = 'upvote') AS upvotes,
                 (SELECT COUNT(*) FROM review_votes WHERE review_id = r.review_id AND vote_type = 'downvote') AS downvotes";

// If user is logged in, add their vote status
if (isset($_SESSION['user_id'])) {
    $query .= ", (SELECT vote_type FROM review_votes WHERE review_id = r.review_id AND user_id = ?) AS user_vote";
}

$query .= " FROM reviews r
          LEFT JOIN users u ON r.user_id = u.user_id
          LEFT JOIN professors p ON r.target_type = 'professor' AND r.target_id = p.id
          LEFT JOIN courses c ON r.target_type = 'course' AND r.target_id = c.course_id
          WHERE r.target_type = ?";

$params = [];
$types = '';

// Add user_id to params if logged in
if (isset($_SESSION['user_id'])) {
    $params[] = $_SESSION['user_id'];
    $types .= 'i';
}

$params[] = $review_type;
$types .= 's';

if (!empty($department)) {
    $query .= " AND p.department = ?";
    $params[] = $department;
    $types .= 's';
}

if (!empty($course)) {
    $query .= " AND c.code = ?";
    $params[] = $course;
    $types .= 's';
}

$query .= " ORDER BY r.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Feed | Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/reviews_feed.css">
    <style>
        /* Add styles for the vote buttons */
        .vote-buttons {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .vote-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            margin-right: 5px;
            opacity: 0.6;
            transition: all 0.2s;
        }
        .vote-btn:hover {
            transform: scale(1.1);
            opacity: 1;
        }
        .vote-btn.active {
            opacity: 1;
        }
        .vote-count {
            margin: 0 8px;
            font-size: 0.9rem;
        }
        .vote-message {
            color: #666;
            font-size: 0.8rem;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📚 Review Feed</h1>

```
    <form method="GET" class="filter-form">
        <label for="review_type">Review Type:</label>
        <select name="review_type" id="review_type">
            <option value="professor" <?= $review_type == 'professor' ? 'selected' : '' ?>>Professor</option>
            <option value="course" <?= $review_type == 'course' ? 'selected' : '' ?>>Course</option>
        </select>

        <div>
            <label for="department">Department:</label>
            <select name="department" id="department">
                <option value="">-- All Departments --</option>
                <?php while ($dept = mysqli_fetch_assoc($departments_result)): ?>
                    <option value="<?= htmlspecialchars($dept['department']) ?>" 
                        <?= $department == $dept['department'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($dept['department']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="course">Course:</label>
            <select name="course" id="course">
                <option value="">-- All Courses --</option>
                <?php while ($courseRow = mysqli_fetch_assoc($courses_result)): ?>
                    <option value="<?= htmlspecialchars($courseRow['code']) ?>" 
                        <?= $course == $courseRow['code'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($courseRow['code']) ?> - <?= htmlspecialchars($courseRow['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit">Filter</button>
        <a href="reviews_feed.php" class="clear-btn">Clear Filters</a>
    </form>

    <!-- Feed -->
    <div class="reviews-feed">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-card">
                    <div class="review-header">
                        <?php if ($review_type == 'professor'): ?>
                            <h3>👩‍🏫 <?= htmlspecialchars($row['professor_name']) ?> (<?= htmlspecialchars($row['department']) ?>)</h3>
                        <?php else: ?>
                            <h3>📘 <?= htmlspecialchars($row['course_code']) ?> - <?= htmlspecialchars($row['course_title']) ?></h3>
                        <?php endif; ?>
                        <p class="review-meta">By <?= htmlspecialchars($row['reviewer_name'] ?? 'Anonymous') ?> on <?= date('F j, Y', strtotime($row['created_at'])) ?></p>
                    </div>
                    <div class="review-body">
                        <p class="rating">⭐ <strong><?= $row['rating'] ?>/5</strong></p>
                        <p class="comment"><?= nl2br(htmlspecialchars($row['comment'])) ?></p>
                        
                        <!-- Add vote buttons -->
                        <div class="vote-buttons">
                            <span>Helpful?</span>
                            <?php 
                            $upvoteActive = isset($row['user_vote']) && $row['user_vote'] === 'upvote' ? 'active' : '';
                            $downvoteActive = isset($row['user_vote']) && $row['user_vote'] === 'downvote' ? 'active' : '';
                            ?>
                            <button class="vote-btn upvote-btn <?= $upvoteActive ?>" data-review-id="<?= $row['review_id'] ?>" data-vote-type="upvote" title="Thumbs Up">
                                👍
                            </button>
                            <span class="vote-count upvotes"><?= $row['upvotes'] ?? 0 ?></span>
                            
                            <button class="vote-btn downvote-btn <?= $downvoteActive ?>" data-review-id="<?= $row['review_id'] ?>" data-vote-type="downvote" title="Thumbs Down">
                                👎
                            </button>
                            <span class="vote-count downvotes"><?= $row['downvotes'] ?? 0 ?></span>
                            
                            <span class="vote-message"></span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reviews found for your filters. Try adjusting them.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all vote buttons
        const voteButtons = document.querySelectorAll('.vote-btn');
        
        voteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Check if user is logged in
                <?php if (!isset($_SESSION['user_id'])): ?>
                    alert('You must be logged in to vote on reviews.');
                    return;
                <?php endif; ?>
                
                const reviewId = this.getAttribute('data-review-id');
                const voteType = this.getAttribute('data-vote-type');
                const reviewCard = this.closest('.review-card');
                const upvoteBtn = reviewCard.querySelector('.upvote-btn');
                const downvoteBtn = reviewCard.querySelector('.downvote-btn');
                const upvoteCount = reviewCard.querySelector('.upvotes');
                const downvoteCount = reviewCard.querySelector('.downvotes');
                const voteMessage = reviewCard.querySelector('.vote-message');
                
                // Create form data
                const formData = new FormData();
                formData.append('review_id', reviewId);
                formData.append('vote_type', voteType);
                
                // Send AJAX request
                fetch('vote_review.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI based on response
                        if (voteType === 'upvote') {
                            if (data.action === 'added') {
                                upvoteBtn.classList.add('active');
                                downvoteBtn.classList.remove('active');
                                upvoteCount.textContent = parseInt(upvoteCount.textContent) + 1;
                                if (downvoteBtn.classList.contains('active')) {
                                    downvoteCount.textContent = parseInt(downvoteCount.textContent) - 1;
                                }
                                voteMessage.textContent = 'You marked this review as helpful.';
                            } else if (data.action === 'removed') {
                                upvoteBtn.classList.remove('active');
                                upvoteCount.textContent = parseInt(upvoteCount.textContent) - 1;
                                voteMessage.textContent = '';
                            } else if (data.action === 'changed') {
                                upvoteBtn.classList.add('active');
                                downvoteBtn.classList.remove('active');
                                upvoteCount.textContent = parseInt(upvoteCount.textContent) + 1;
                                downvoteCount.textContent = parseInt(downvoteCount.textContent) - 1;
                                voteMessage.textContent = 'You marked this review as helpful.';
                            }
                        } else if (voteType === 'downvote') {
                            if (data.action === 'added') {
                                downvoteBtn.classList.add('active');
                                upvoteBtn.classList.remove('active');
                                downvoteCount.textContent = parseInt(downvoteCount.textContent) + 1;
                                if (upvoteBtn.classList.contains('active')) {
                                    upvoteCount.textContent = parseInt(upvoteCount.textContent) - 1;
                                }
                                voteMessage.textContent = 'You marked this review as not helpful.';
                            } else if (data.action === 'removed') {
                                downvoteBtn.classList.remove('active');
                                downvoteCount.textContent = parseInt(downvoteCount.textContent) - 1;
                                voteMessage.textContent = '';
                            } else if (data.action === 'changed') {
                                downvoteBtn.classList.add('active');
                                upvoteBtn.classList.remove('active');
                                downvoteCount.textContent = parseInt(downvoteCount.textContent) + 1;
                                upvoteCount.textContent = parseInt(upvoteCount.textContent) - 1;
                                voteMessage.textContent = 'You marked this review as not helpful.';
                            }
                        }
                    } else {
                        alert(data.message || 'An error occurred while voting.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your vote.');
                });
            });
        });
    });
</script>
```

</body>
</html>

