<?php
require '../controllers/auth.php';
require '../config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Fetch requisitions for the logged-in user
$query = "SELECT id, office, status, created_at FROM requisitions WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Requisition List</title>
</head>
<body>
    <h2>My Requisitions</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Office</th>
            <th>Status</th>
            <th>Requested On</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["id"]); ?></td>
                <td><?= htmlspecialchars($row["office"]); ?></td>
                <td><?= isset($row["status"]) ? htmlspecialchars($row["status"]) : "Pending"; ?></td>
                <td><?= htmlspecialchars($row["created_at"]); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
