<?php
session_start();
require_once '../config/db.php';

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../home.php');
    exit();
}

// Total counts
$users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$reviews = $conn->query("SELECT COUNT(*) as count FROM reviews")->fetch_assoc()['count'];
$professors = $conn->query("SELECT COUNT(*) as count FROM professors")->fetch_assoc()['count'];
$courses = $conn->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];

// Most reviewed professor
$top_prof = $conn->query("
    SELECT p.name, COUNT(*) as review_count 
    FROM reviews r 
    JOIN professors p ON r.target_id = p.id 
    WHERE r.target_type = 'professor' 
    GROUP BY p.id 
    ORDER BY review_count DESC 
    LIMIT 1
")->fetch_assoc();

// Most reviewed course
$top_course = $conn->query("
    SELECT c.title, COUNT(*) as review_count 
    FROM reviews r 
    JOIN courses c ON r.target_id = c.course_id 
    WHERE r.target_type = 'course' 
    GROUP BY c.course_id 
    ORDER BY review_count DESC 
    LIMIT 1
")->fetch_assoc();

// Get users list for management
$users_list = $conn->query("SELECT user_id, name, email, role, created_at FROM users");

// Get recent reviews for moderation
$recent_reviews = $conn->query("SELECT r.review_id, r.rating, r.comment, r.sentiment, u.name, p.name AS professor_name, c.title AS course_title, r.target_type
FROM reviews r
LEFT JOIN users u ON r.user_id = u.user_id
LEFT JOIN professors p ON r.target_id = p.id AND r.target_type = 'professor'
LEFT JOIN courses c ON r.target_id = c.course_id AND r.target_type = 'course'
ORDER BY r.created_at DESC LIMIT 5");

// Reviews per month (last 6 months)
$monthly_reviews = [];
$labels = [];

for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $label = date('M Y', strtotime("-$i months"));
    $labels[] = $label;

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM reviews WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $monthly_reviews[] = $res['count'] ?? 0;
}

// User role distribution
$role_data = [];
$roles = ['admin', 'student'];

foreach ($roles as $role) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = ?");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $role_data[$role] = $res['count'] ?? 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/admin_dashboard.css">
</head>
<body>
    <header class="admin-header">
        <h2>Admin Dashboard</h2>
        <nav class="admin-nav">
            <a href="../php_files/logout.php">Logout</a>
        </nav>
    </header>

    <main class="admin-main">
        <section class="stats-grid">
            <div class="stat-card"><h4>Total Users</h4><p><?= $users ?></p></div>
            <div class="stat-card"><h4>Total Reviews</h4><p><?= $reviews ?></p></div>
            <div class="stat-card"><h4>Professors</h4><p><?= $professors ?></p></div>
            <div class="stat-card"><h4>Courses</h4><p><?= $courses ?></p></div>
        </section>

        <section class="top-items">
            <div class="top-card">
                <h4>Most Reviewed Professor</h4>
                <p><?= $top_prof['name'] ?? 'N/A' ?> (<?= $top_prof['review_count'] ?? 0 ?> reviews)</p>
            </div>
            <div class="top-card">
                <h4>Most Reviewed Course</h4>
                <p><?= $top_course['title'] ?? 'N/A' ?> (<?= $top_course['review_count'] ?? 0 ?> reviews)</p>
            </div>
        </section>

        <section class="manage-users">
            <h4>Manage Users</h4>
            <table>
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Role</th><th>Created At</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php while ($user = $users_list->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['name']  ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="delete_user.php?id=<?= $user['user_id'] ?>">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="manage-reviews">
            <h4>Recent Reviews</h4>
            <table>
                <thead>
                    <tr><th>User</th><th>Rating</th><th>Review</th><th>Professor</th><th>Course</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php while ($review = $recent_reviews->fetch_assoc()): ?>
                    <tr <?php if ($review['sentiment'] === 'negative') echo 'style="background-color: #ffdddd;"'; ?>>
                        <td><?= $review['name'] ?></td>
                        <td><?= $review['rating'] ?>/5</td>
                        <td><?= $review['comment'] ?></td>
                        <td><?= $review['professor_name'] ?? 'N/A' ?></td>
                        <td><?= $review['course_title'] ?? 'N/A' ?></td>
                        <td>
                            <a href="delete_review.php?id=<?= $review['review_id'] ?>">Delete</a>
                            <td>
                                <?php if ($review['sentiment'] === 'negative'): ?>
                                    <span style="color: red; font-weight: bold;">⚠️ Flagged</span>
                                <?php else: ?>
                                    <span style="color: green;">✓ OK</span>
                                <?php endif; ?>
                            </td>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- MANAGE PROFESSORS -->
        <section class="manage-professors">
            <h4>Manage Professors</h4>
            <a href="add_professor.php">Add Professor</a>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $professors = mysqli_query($conn, "SELECT * FROM professors");

                    while ($professor = mysqli_fetch_assoc($professors)) {
                        echo "<tr>";
                        echo "<td>{$professor['name']}</td>";
                        echo "<td>{$professor['department']}</td>";
                        echo "<td>
                                <a href='edit_professor.php?id={$professor['id']}'>Edit</a> | 
                                <a href='delete_professor.php?id={$professor['id']}'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <!-- MANAGE COURSES -->
        <section class="manage-courses">
            <h4>Manage Courses</h4>
            <a href="add_course.php">Add Course</a>
            <table>
                <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Course Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $courses = mysqli_query($conn, "SELECT * FROM courses");

                    while ($course = mysqli_fetch_assoc($courses)) {
                        echo "<tr>";
                        echo "<td>{$course['title']}</td>";
                        echo "<td>{$course['code']}</td>";
                        echo "<td>
                                <a href='edit_course.php?id={$course['course_id']}'>Edit</a> | 
                                <a href='delete_course.php?id={$course['course_id']}'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="analytics">
            <h4>Dashboard Analytics</h4>
            <div class="charts">
                <div class="chart-container">
                    <canvas id="reviewsChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>
        </section>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Reviews per Month
        const reviewsCtx = document.getElementById('reviewsChart').getContext('2d');
        const reviewsChart = new Chart(reviewsCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Reviews per Month',
                    data: <?= json_encode($monthly_reviews) ?>,
                    backgroundColor: '#D6A7BA',
                    borderColor: '#450028',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true
            }
        });

        // User Role Distribution
        const rolesCtx = document.getElementById('rolesChart').getContext('2d');
        const rolesChart = new Chart(rolesCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_keys($role_data)) ?>,
                datasets: [{
                    label: 'User Roles',
                    data: <?= json_encode(array_values($role_data)) ?>,
                    backgroundColor: ['#450028', '#D6A7BA'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</body>
</html>
