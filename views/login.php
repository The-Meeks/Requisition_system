<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <form action="../controllers/login.php" method="post">
        <h2>Login</h2>
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>
