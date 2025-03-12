<?php 
session_start();
require '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Unauthorized access!";
    header("Location: login.php");
    exit();
}

// Fetch all requisitions with requester and approver details
$query = "SELECT r.id, r.user_id, r.created_at, r.head_office_approval, 
                 r.head_office_approver, 
                 u.first_name AS requester_name, 
                 ho.first_name AS approver_name
          FROM requisitions r
          JOIN users u ON r.user_id = u.id
          LEFT JOIN users ho ON r.head_office_approver = ho.first_name"; // Match first name of the approver

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition List</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <h2>Requisition List</h2>

    <table>
        <tr>
            <th>Requisition ID</th>
            <th>Requested By</th>
            <th>Request Date</th>
            <th>Approval Status</th>
            <th>Approved By</th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['requester_name']}</td>
                        <td>{$row['created_at']}</td>
                        <td>" . (!empty($row['head_office_approval']) ? $row['head_office_approval'] : "Pending") . "</td>
                        <td>" . (!empty($row['approver_name']) ? $row['approver_name'] : "Pending") . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No requisitions found.</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
