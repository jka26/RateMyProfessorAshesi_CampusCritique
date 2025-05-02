<?php
session_start();
require_once('../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $department = trim($_POST['department']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../home.php");
        exit();
    }

    // // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in users table
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "An account with that email already exists.";
        header("Location: ../home.php");
        exit();
    }

    $role = 'professor';

    // Insert into users table first (with role = professor)
    $userInsert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $userInsert->bind_param("ssss", $name, $email, $hashedPassword, $role);


    if (!$userInsert->execute()) {
        $_SESSION['error'] = "Failed to create professor account in users table.";
        header("Location: ../home.php");
        exit();
    }
 

    // Insert into professors table
    $stmt = $conn->prepare("INSERT INTO professors (name, department, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $department, $email, $hashedPassword, $role);

    if (!$stmt->execute()) {
        $_SESSION['error'] = "Failed to create professor profile.";
        header("Location: ../home.php");
        exit();
    }

    // Success
    $_SESSION['success'] = "Professor account created successfully. Please login.";
    header("Location: ../home.php");  // Send to login page
    

    $userInsert->close();
    $stmt->close();
    $conn->close();
}
?>