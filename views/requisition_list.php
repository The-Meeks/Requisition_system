<?php
require '../controllers/auth.php';
require '../config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM requisitions WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <h2>My Requisitions</h2>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Unit of Issue</th>
            <th>Quantity</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row["office"]) ?></td>
                <td><?= htmlspecialchars($row["status"]) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
