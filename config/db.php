<?php
$host = 'localhost';
$db = 'ratemyplatform';
$user = 'root';
$pass = ''; // default password in XAMPP is blank

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
