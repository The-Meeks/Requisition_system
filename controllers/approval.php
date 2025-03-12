<?php
session_start();
require '../config/db.php';
require '../controllers/auth.php';

// Ensure user is logged in and has the appropriate role
if (!isLoggedIn() || !hasRole('head_office')) {
    $_SESSION['error'] = "Unauthorized access. Please log in to continue.";

    header("Location: ../views/login.php");
    exit();
}

// Example of approving a requisition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['requisition_id'])) {
    $requisition_id = intval($_POST['requisition_id']);
    $stmt = $conn->prepare("UPDATE requisitions SET head_office_approval = 'Approved' WHERE id = ?");
    $stmt->bind_param("i", $requisition_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Requisition approved successfully.";
    } else {
    $_SESSION['error'] = "Error approving requisition. Please try again later.";

    }
    header("Location: ../views/head_office.php");
    exit();
}
?>
