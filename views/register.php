<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <?php session_start(); ?>
    
    <form action="../controllers/register.php" method="post">
        <h2>Register</h2>

        <!-- Display Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <!-- Display Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <label>First Name:</label>
        <input type="text" name="first_name" required>
        <label>Last Name:</label>
        <input type="text" name="last_name" required>
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Select Role:</label>
        <select name="role" required>
            <option value="requester">Requester</option>
            <option value="head_office">Head of Office</option>
            <option value="dda">DDA Authorization</option>
            <option value="ddfa">DDFA Funds Approval</option>
            <option value="ddnrc">DDNRC Approval</option>
            <option value="issuer">Issuer</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Register</button>
    </form>
    
    <p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
