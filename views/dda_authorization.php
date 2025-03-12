<?php
session_start();
require '../config/db.php';
require '../controllers/auth.php';

// Ensure user is logged in and has the DDA role
check_auth();
check_role(['dda']);

// Fetch pending requisitions for DDA approval
$query = "SELECT id, user_id, office, created_at, status, head_office_approval, head_office_approver, head_office_date 
          FROM requisitions 
          WHERE head_office_approval = 'Recommended' AND dda_approval = 'Pending'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDA Authorization</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <h2>DDA Authorization</h2>

    <?php if (isset($_SESSION["message"])): ?>
        <p><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Requisition ID</th>
            <th>Office</th>
            <th>Requested By</th>
            <th>Request Date</th>
            <th>Head Office Approval</th>
            <th>Head Office Approver</th>
            <th>Approval Date</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["office"]; ?></td>
                    <td><?php echo $row["user_id"]; ?></td>
                    <td><?php echo $row["created_at"]; ?></td>
                    <td><?php echo $row["head_office_approval"]; ?></td>
                    <td><?php echo $row["head_office_approver"]; ?></td>
                    <td><?php echo $row["head_office_date"]; ?></td>
                    <td>
                        <form method="POST" action="../controllers/process_dda_authorization.php">
                            <input type="hidden" name="requisition_id" value="<?php echo $row['id']; ?>">
                            <select name="approval" required>
                                <option value="Authorized">Authorize</option>
                                <option value="Not Authorized">Not Authorize</option>
                            </select>
                            <button type="submit">Submit</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No pending requisitions for DDA authorization.</td></tr>
        <?php endif; ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
