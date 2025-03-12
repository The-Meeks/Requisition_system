<?php
session_start();
require '../config/db.php';

// Ensure only POST requests are processed
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requisition_id = trim($_POST["requisition_id"]);
    $approval_status = trim($_POST["approval"]);
    $approver_id = $_SESSION["user_id"]; // Get the logged-in DDA's ID

    // Fetch the first name of the DDA approver
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $approver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $approver_name = $row["first_name"];
    } else {
        $_SESSION["message"] = "Error: Approver not found!";
        header("Location: ../views/dda_authorization.php");
        exit();
    }

    $stmt->close();

    // Update the requisition with DDA approval details
    $stmt = $conn->prepare("UPDATE requisitions 
                            SET dda_approval = ?, 
                                dda_approver = ?, 
                                dda_date = NOW() 
                            WHERE id = ?");
    $stmt->bind_param("ssi", $approval_status, $approver_name, $requisition_id);

    if ($stmt->execute()) {
        $_SESSION["message"] = "Requisition successfully updated!";
    } else {
        $_SESSION["message"] = "Error updating requisition!";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to DDA authorization page
    header("Location: ../views/dda_authorization.php");
    exit();
}
?>
