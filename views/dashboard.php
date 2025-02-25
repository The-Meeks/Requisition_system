<?php
session_start();
require '../controllers/auth.php';
require '../config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT first_name, last_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user["first_name"]) . " " . htmlspecialchars($user["last_name"]); ?>!</h2>
    <p>Role: <?= htmlspecialchars($user["role"]); ?></p>
    <nav>
        <ul>
            <li><a href="requisition_form.php">Make a Requisition</a></li>
            <li><a href="requisition_list.php">View My Requisitions</a></li>
        </ul>
    </nav>
    <a href="../controllers/logout.php">Logout</a>
</body>
</html>
