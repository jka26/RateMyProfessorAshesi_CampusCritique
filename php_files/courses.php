<?php
session_start();
require_once('../config/db.php');

$query = "SELECT * FROM courses";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses | Campus Critique</title>
    <link rel="stylesheet" href="../public/styles/courses.css">
</head>
<body>
    <header>
        <a href="dashboard.php">← Back to Dashboard</a>
    </header>
    <h1>Courses Offered at Ashesi</h1>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <li>
                <a href="course_details.php?id=<?= $row['course_id'] ?>" class="course-link">
                    <?php echo htmlspecialchars($row['code']) . " - " . htmlspecialchars($row['title']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <section class="footer">
        <p>&copy; <?php echo date("Y"); ?> Campus Critique. All rights reserved.</p>
        <div class="social-media">
            <a href="#"><img src="../RateMyProfessorAshesi/assets/facebook.jpeg" alt="Facebook"></a>
            <a href="#"><img src="../RateMyProfessorAshesi/assets/Twitter.png" alt="Twitter"></a>
            <a href="#"><img src="../RateMyProfessorAshesi/assets/instagram.jpeg" alt="Instagram"></a>
        </div>
    </section>
</body>
</html>
