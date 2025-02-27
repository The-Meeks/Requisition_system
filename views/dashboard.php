<?php
session_start();
require '../controllers/auth.php';
require '../config/db.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user details
$stmt = $conn->prepare("SELECT first_name, last_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Store role in session if not already set
if (!isset($_SESSION["role"]) || empty($_SESSION["role"])) {
    $_SESSION["role"] = $user["role"];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($user["first_name"]) . " " . htmlspecialchars($user["last_name"]); ?>!</h2>
    <p>Role: <?= htmlspecialchars($_SESSION["role"]); ?></p>

    <nav>
        <ul>
            <li><a href="requisition_form.php">Make a Requisition</a></li>
            <li><a href="requisition_list.php">View My Requisitions</a></li>

            <!-- Display approval links based on user role -->
            <?php if ($_SESSION["role"] == "head_office"): ?>
                <li><a href="/requisition_system/views/head_office.php">Review Requisitions</a></li>
            <?php elseif ($_SESSION["role"] == "dda"): ?>
                <li><a href="dda_authorization.php">Authorize Requisitions</a></li>
            <?php elseif ($_SESSION["role"] == "ddfa"): ?>
                <li><a href="ddfa_funds_approval.php">Approve Funds</a></li>
            <?php elseif ($_SESSION["role"] == "ddnrc"): ?>
                <li><a href="ddnrc_approval.php">Final Approval</a></li>
            <?php elseif ($_SESSION["role"] == "issuer"): ?>
                <li><a href="issuance.php">Issue Items</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <a href="../controllers/logout.php">Logout</a>
</body>
</html>
