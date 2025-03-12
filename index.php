<?php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: views/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <h2>Welcome to the Requisition System</h2>
    <a href="views/login.php">Login</a> | <a href="views/register.php">Register</a>
</body>
</html>
