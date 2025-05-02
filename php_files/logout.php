<?php
session_start();
session_unset();
session_destroy();

header("Location: ../home.php"); // Redirect to homepage
exit();
