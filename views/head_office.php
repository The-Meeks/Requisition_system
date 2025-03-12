<?php
session_start();
require '../config/db.php';

// Ensure the user is logged in and has the correct role
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "head_office") {
    $_SESSION["message"] = "Unauthorized access!";
    header("Location: login.php");
    exit();
}

// Fetch all pending requisitions
$query = "SELECT r.id, u.first_name, u.last_name, r.office, r.created_at, 
                 r.head_office_approval, r.head_office_approver
          FROM requisitions r
          JOIN users u ON r.user_id = u.id
          WHERE r.head_office_approval = 'Pending'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Head Office Approval</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <h2>Head Office Approval</h2>

    <?php
    if (isset($_SESSION["message"])) {
        echo "<p>{$_SESSION['message']}</p>";
        unset($_SESSION["message"]); // Clear message after displaying
    }
    ?>

    <table border="1">
        <tr>
            <th>Requester</th>
            <th>Office</th>
            <th>Request Date</th>
            <th>Approval Status</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                    <td><?= htmlspecialchars($row['office']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td><?= htmlspecialchars($row['head_office_approval']) ?></td>
                    <td>
                        <form method="POST" action="../controllers/head_office_approval.php">
                            <input type="hidden" name="requisition_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="approval" value="Recommended">Recommend</button>
                            <button type="submit" name="approval" value="Not Recommended">Not Recommend</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No pending requisitions.</td></tr>
        <?php endif; ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
