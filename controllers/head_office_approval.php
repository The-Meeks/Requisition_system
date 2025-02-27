<?php
session_start();
require '../config/db.php';
require '../controllers/auth.php';

// Ensure user is logged in and has the "head_office" role
if (!isLoggedIn() || !hasRole('head_office')) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requisition_id = $_POST['requisition_id'];
    $decision = $_POST['decision']; // "Recommended" or "Not Recommended"
    $approver_id = $_SESSION['user_id']; // Logged-in user ID
    $approval_date = date('Y-m-d H:i:s'); // Current date & time

    // Fetch approver's first name
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $approver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $approver_name = $result->fetch_assoc()['first_name'] ?? 'Unknown';

    // Update requisition with approval decision, approver's name, and date
    $stmt = $conn->prepare("UPDATE requisitions SET 
        head_office_approval = ?, 
        head_office_approver = ?, 
        head_office_date = ? 
        WHERE id = ?");
    $stmt->bind_param("sssi", $decision, $approver_name, $approval_date, $requisition_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Requisition successfully updated!";
    } else {
        $_SESSION['error'] = "Error updating requisition.";
    }

    header("Location: ../views/head_office.php");
    exit();
}
?>
