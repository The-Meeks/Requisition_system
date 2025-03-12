<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT first_name, last_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user["first_name"]) . " " . htmlspecialchars($user["last_name"]); ?>!</h2>
    <p>Role: <?= htmlspecialchars($_SESSION["role"]); ?></p>

    <nav>
        <ul>
            <li><a href="requisition_form.php">Make a Requisition</a></li>
            <li><a href="requisition_list.php">View My Requisitions</a></li>
            <?php if ($_SESSION["role"] == "head_office"): ?>
                <li><a href="head_office.php">Review Requisitions</a></li>
            <?php elseif ($_SESSION["role"] == "dda"): ?>
                <li><a href="dda_authorization.php">Authorize Requisitions</a></li>
            <?php elseif ($_SESSION["role"] == "ddfa"): ?>
                <li><a href="ddfa_approval.php">Approve Funds</a></li>
            <?php elseif ($_SESSION["role"] == "ddnrc"): ?>
                <li><a href="ddnrc_approval.php">Final Approval</a></li>
            <?php elseif ($_SESSION["role"] == "issuer"): ?>
                <li><a href="collection.php">Issue Items</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <a href="../logout.php">Logout</a>
</body>
</html>
