<?php
session_start();
require_once '../config/db.php';

// Fetch stats
$avg_rating = 0;
$total_reviews = 0;
$total_users = 0;
$top_professor = "N/A";
$top_course = "N/A";

// Average rating
$rating_sql = "SELECT AVG(rating) AS avg_rating FROM reviews";
$rating_result = $conn->query($rating_sql);
if ($rating_result && $row = $rating_result->fetch_assoc()) {
    $avg_rating = round($row['avg_rating'], 2);
}

// Total reviews
$count_reviews_sql = "SELECT COUNT(*) AS total_reviews FROM reviews";
$review_result = $conn->query($count_reviews_sql);
if ($review_result && $row = $review_result->fetch_assoc()) {
    $total_reviews = $row['total_reviews'];
}

// Total users
$count_users_sql = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($count_users_sql);
if ($user_result && $row = $user_result->fetch_assoc()) {
    $total_users = $row['total_users'];
}

// Most reviewed professor
$prof_sql = "
    SELECT p.name, COUNT(*) AS count 
    FROM reviews r 
    JOIN professors p ON r.target_id = p.id 
    WHERE r.target_type = 'professor' 
    GROUP BY p.id 
    ORDER BY count DESC 
    LIMIT 1
";
$prof_result = $conn->query($prof_sql);
if ($prof_result && $row = $prof_result->fetch_assoc()) {
    $top_professor = $row['name'];
}

// Most reviewed course
$course_sql = "
    SELECT c.title, COUNT(*) AS count 
    FROM reviews r 
    JOIN courses c ON r.target_id = c.course_id 
    WHERE r.target_type = 'course' 
    GROUP BY c.course_id 
    ORDER BY count DESC 
    LIMIT 1
";
$course_result = $conn->query($course_sql);
if ($course_result && $row = $course_result->fetch_assoc()) {
    $top_course = $row['title'];
}

$user_id = $_SESSION['user_id'] ?? null;
$monthly_data = [];

if ($user_id) {
    $query = "
        SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count
        FROM reviews
        WHERE user_id = $user_id
        GROUP BY month
    ";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $monthly_data[$row['month']] = $row['count'];
    }
}


    $suggestions = [
        'professors' => [],
        'courses' => []
    ];

    // Top 3 reviewed professors
    $prof_query = "
        SELECT p.name, COUNT(*) as review_count 
        FROM reviews r 
        JOIN professors p ON r.target_type = 'professor' AND r.target_id = p.id 
        GROUP BY p.id 
        ORDER BY review_count DESC LIMIT 3
    ";
    $prof_result = $conn->query($prof_query);
    while ($row = $prof_result->fetch_assoc()) {
        $suggestions['professors'][] = $row;
    }

    // Top 3 reviewed courses
    $course_query = "
        SELECT c.title, COUNT(*) as review_count 
        FROM reviews r 
        JOIN courses c ON r.target_type = 'course' AND r.target_id = c.course_id 
        GROUP BY c.course_id 
        ORDER BY review_count DESC LIMIT 3
    ";
    $course_result = $conn->query($course_query);
    while ($row = $course_result->fetch_assoc()) {
        $suggestions['courses'][] = $row;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
    <header class="dashboard-header">
        <h2>Welcome to Campus Critique</h2>
        <nav class="dashboard-nav">
            <a href="../home.php">Home</a>
            <a href="profile.php">My Profile</a>
            <a href="reviews_feed.php">Feed</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-main">
        <section class="welcome-box">
            <h3>Hello, <?= $_SESSION['user_name'] ?? 'Student'; ?>!</h3>
            <p>Explore ratings, write reviews, and track your feedback history.</p>
        </section>


        <section class="quick-actions">
            <div class="card">
                <h4>Browse Professors</h4>
                <p>View ratings for professors across departments.</p>
                <a href="professors.php" class="action-btn">Explore</a>
            </div>
            <div class="card">
                <h4>Review a Course</h4>
                <p>Share your thoughts and experiences on a course.</p>
                <a href="courses.php" class="action-btn">Review</a>
            </div>
            <div class="card">
                <h4>My Reviews</h4>
                <p>See the feedback you've submitted.</p>
                <a href="my_reviews.php" class="action-btn">View</a>
            </div>
        </section>
        <section class="top-reviewed">
            <div class="top-card">
                <h4>Top Reviewed Professor</h4>
                <p><?= $top_professor ?></p>
            </div>
            <div class="top-card">
                <h4>Top Reviewed Course</h4>
                <p><?= $top_course ?></p>
            </div>
        </section>
        <section class="activity-chart">
            <h3>My Review Activity</h3>
            <canvas id="reviewChart"></canvas>
        </section>

        <section class="suggestions">
            <h3>Trending Picks for You</h3>
            <div class="suggestion-grid">
                <div class="suggestion-box">
                    <h4>Top Professors</h4>
                    <ul>
                        <?php foreach ($suggestions['professors'] as $prof): ?>
                            <li><?= $prof['name'] ?> (<?= $prof['review_count'] ?> reviews)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="suggestion-box">
                    <h4>Top Courses</h4>
                    <ul>
                        <?php foreach ($suggestions['courses'] as $course): ?>
                            <li><?= $course['title'] ?> (<?= $course['review_count'] ?> reviews)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <script>
            const ctx = document.getElementById('reviewChart').getContext('2d');
            const reviewChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_keys($monthly_data)) ?>,
                    datasets: [{
                        label: 'Reviews Submitted',
                        data: <?= json_encode(array_values($monthly_data)) ?>,
                        backgroundColor: '#D6A7BA',
                        borderColor: '#450028',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>

    </main>
</body>
</html>
