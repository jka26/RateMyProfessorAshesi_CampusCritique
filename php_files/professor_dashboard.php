<?php
session_start();
require_once '../config/db.php';

// Access control: only professors
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'professor') {
    header("Location: ../home.php");
    exit();
}

// Fetch current professor data
$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM professors WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$professor = $result->fetch_assoc();
if (!$professor) {
    $_SESSION['error'] = "No professor profile found.";
    header("Location: ../home.php");
    exit();
}

// Handle form submission (profile update)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);
    $new_email = trim($_POST['email']); // New email entered

    $update = $conn->prepare("UPDATE professors SET name = ?, department = ?, email = ? WHERE email = ?");
    $update->bind_param("ssss", $name, $department, $new_email, $email);

    if ($update->execute()) {
        $_SESSION['email'] = $new_email; // Update session email
        $_SESSION['success'] = "Profile updated!";
        header("Location: professor_dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Update failed.";
    }
}

// Fetch reviews made about the professor
$professor_id = $professor['id'];
$review_stats = $conn->query("SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM reviews WHERE target_type = 'professor' AND target_id = $professor_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="../public/styles/professor_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <div class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars($professor['name']) ?> 👨‍🏫</h1>
            <!-- <p>Professor Dashboard</p> -->
        </div>

        <div class="section stats">
            <div class="stat-box">
                <h2><?= $review_stats['total_reviews'] ?? 0 ?></h2>
                <p>Total Reviews Received</p>
            </div>
            <div class="stat-box">
                <h2><?= round($review_stats['avg_rating'], 1) ?? 'N/A' ?></h2>
                <p>Average Rating</p>
            </div>
        </div>

        <div class="section">
            <h3>Edit Your Profile</h3>
            <form method="POST">
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($professor['name']) ?>" required>

                <label for="department">Department:</label>
                <input type="text" name="department" id="department" value="<?= htmlspecialchars($professor['department']) ?>" required>

                <label for="email">Email Address:</label>
                <input type="text" name="email" id="email" value="<?= htmlspecialchars($professor['email']) ?>" readonly>

                <button type="submit" class="btn">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>
