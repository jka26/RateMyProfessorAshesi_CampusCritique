<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data safely
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username = trim($_POST['username']);

    $verification_token = bin2hex(random_bytes(16));
    $is_verified = 0;


    // Validate fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($username)) {
        $_SESSION['signup_error'] = "Please fill in all fields.";
        header("Location: ../home.php");
        exit();
    }

    // Check password match
    if ($password !== $confirm_password) {
        $_SESSION['signup_error'] = "Passwords do not match.";
        header("Location: ../home.php");
        exit();
    }
    

    // Check if email is Ashesi email
    if (!preg_match("/@ashesi\.edu\.gh$/", $email)) {
        $_SESSION['signup_error'] = "Only Ashesi email addresses are allowed.";
        header("Location: ../home.php");
        exit();
    }


    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $emailResult = $checkEmail->get_result();

    if ($emailResult->num_rows > 0) {
        $_SESSION['signup_error'] = "An account with that email already exists.";
        header("Location: ../home.php");
        exit();
    }

    $checkEmail->close(); // Important to close

    // Check if username already exists
    $checkUsername = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $usernameResult = $checkUsername->get_result();

    if ($usernameResult->num_rows > 0) {
        $_SESSION['signup_error'] = "The username is already taken.";
        header("Location: ../home.php");
        exit();
    }


    $checkUsername->close(); // Important to close

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user (let database auto-generate user_id)
    $role = 'student';
    $insert = $conn->prepare("INSERT INTO users (name, email, password, username, is_verified, verification_token) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssis", $name, $email, $hashedPassword, $username, $is_verified, $verification_token);

    if ($insert->execute()) {
        // Prepare verification email
        $to = $email;
        $subject = "Campus Critique - Verify Your Email";
        $verification_link = "http://yourdomain.com/RateMyProfessorAshesi/php_files/verify.php?token=$verification_token";
        $message = "
            <html>
            <head>
              <title>Verify Your Email</title>
            </head>
            <body>
              <p>Hello $name,</p>
              <p>Thank you for signing up on Campus Critique!</p>
              <p>Please verify your email by clicking the link below:</p>
              <p><a href='$verification_link'>Verify Email</a></p>
              <p>If you did not sign up, you can ignore this email.</p>
            </body>
            </html>
        ";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@yourdomain.com" . "\r\n";
    
        // Send email
        if (mail($to, $subject, $message, $headers)) {
            $_SESSION['signup_success'] = "Signup successful! Check your email to verify your account.";
        } else {
            $_SESSION['signup_error'] = "Signup successful, but failed to send verification email.";
        }
    } else {
        $_SESSION['signup_error'] = "Something went wrong: " . $insert->error;
    }
    

    $insert->close();
    $conn->close();

    header("Location: ../home.php");
    exit();
}
?>
