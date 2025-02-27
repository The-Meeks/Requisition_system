<?php
session_start();
require '../config/db.php';
require '../controllers/auth.php';

if (!isLoggedIn() || !hasRole('head_office')) {
    header("Location: login.php");
    exit();
}

// Fetch pending requisitions with items
$stmt = $conn->prepare("
    SELECT r.id, r.user_id, 
           SUBSTRING_INDEX(u.first_name, ' ', 1) AS requested_by, 
           r.created_at AS requested_on, 
           GROUP_CONCAT(ri.item_name SEPARATOR ', ') AS items, 
           GROUP_CONCAT(ri.unit SEPARATOR ', ') AS units, 
           GROUP_CONCAT(ri.quantity SEPARATOR ', ') AS quantities, 
           r.head_office_approval 
    FROM requisitions r
    JOIN users u ON r.user_id = u.id
    JOIN requisition_items ri ON r.id = ri.requisition_id
    WHERE r.head_office_approval = 'Pending'
    GROUP BY r.id
");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Head of Office Approval</title>
</head>
<body>
    <h2>Head of Office - Approve Requisitions</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Requested By (User ID)</th>
                <th>Requested On</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['requested_by']); ?></td>
                    <td><?= htmlspecialchars($row['requested_on']); ?></td>
                    <td><?= htmlspecialchars($row['items'] ?? 'N/A'); ?></td>
<td><?= htmlspecialchars($row['units'] ?? 'N/A'); ?></td>
<td><?= htmlspecialchars($row['quantities'] ?? 'N/A'); ?></td>
                    <td>
                        <form action="../controllers/head_office_approval.php" method="POST">
                            <input type="hidden" name="requisition_id" value="<?= htmlspecialchars($row['id']); ?>">
                            <button type="submit" name="decision" value="Recommended">Recommend</button>
                            <button type="submit" name="decision" value="Not Recommended">Do Not Recommend</button>
                        </form>
                    </td>
                </tr>
                <!-- Debugging: Print raw data -->
              <!--  <tr>
                    <td colspan="7"><pre> ?></pre></td>
                </tr> -->
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
