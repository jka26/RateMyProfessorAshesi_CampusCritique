<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Please fill in both email and password.";
        header("Location: ../home.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_success'] = true;

            if ($user['role'] === 'admin') {
                header("Location: ../php_files/admin_dashboard.php");
            }
            else if ($user['role'] === 'professor') {
                header("Location: ../php_files/professor_dashboard.php");
            }
            else {
                header("Location: ../php_files/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password.";
    }

    header("Location: ../home.php");
    exit();
}
?>
