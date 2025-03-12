<?php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <form action="../controllers/register.php" method="POST">
        <h2>Register</h2>
        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Last Name:</label>
        <input type="text" name="last_name" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Role:</label>
        <select name="role">
            <option value="requester">Requester</option>
            <option value="head_office">Head Office</option>
            <option value="dda">DDA</option>
            <option value="ddfa">DDFA</option>
            <option value="ddnrc">DDNRC</option>
            <option value="issuer">Issuer</option>
        </select>
        <button type="submit">Register</button>
    </form>
</body>
</html>
