<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    header("Location: views/dashboard.php"); // Redirect to dashboard if logged in
    exit();
} else {
    header("Location: views/login.php"); // Redirect to login if not logged in
    exit();
}
?>
