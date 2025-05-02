<?php
session_start();
include('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    // Handle profile picture upload if necessary

    $update_query = "UPDATE users SET name='$name', username='$username' WHERE user_id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        header("Location: ../php_files/profile.php"); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../public/styles/edit_profile.css">
</head>
<body>
    <header>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="courses.php">Courses</a>
            <a href="professors.php">Professors</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
