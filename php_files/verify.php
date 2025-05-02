<?php
session_start();
require_once('../config/db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Find user with this verification token
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE verification_token = ? AND is_verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Token is valid; update user to set verified
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        $update = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE user_id = ?");
        $update->bind_param("i", $user_id);

        if ($update->execute()) {
            $_SESSION['verification_success'] = "Your email has been successfully verified. You can now log in.";
            header("Location: ../home.php");
            exit();
        } else {
            $_SESSION['verification_error'] = "Verification failed. Please try again.";
            header("Location: ../home.php");
            exit();
        }
    } else {
        $_SESSION['verification_error'] = "Invalid or already used verification link.";
        header("Location: ../home.php");
        exit();
    }
} else {
    $_SESSION['verification_error'] = "Invalid verification attempt.";
    header("Location: ../home.php");
    exit();
}
?>
