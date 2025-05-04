<?php
$host = 'sql8.freesqldatabase.com';     
$db   = 'sql8776863';   
$user = 'sql8776863'; 
$pass = 'LeVtBB6sB2';        

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
