<?php
session_start();
require '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requisition_id = trim($_POST["requisition_id"]);
    $approval_status = trim($_POST["approval"]);
    $approver_id = $_SESSION["user_id"]; // Get the logged-in approver's ID

    // Fetch the first name of the approver
    $stmt = $conn->prepare("SELECT first_name FROM users WHERE id = ?");
    $stmt->bind_param("i", $approver_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $approver_name = $row["first_name"];
    } else {
        $_SESSION["message"] = "Error: Approver not found!";
        header("Location: ../views/head_office.php");
        exit();
    }

    $stmt->close();

    // Update the requisition approval status and store the approver's first name
    $stmt = $conn->prepare("UPDATE requisitions 
                            SET head_office_approval = ?, 
                                head_office_approver = ?, 
                                head_office_date = NOW() 
                            WHERE id = ?");
    $stmt->bind_param("ssi", $approval_status, $approver_name, $requisition_id);

    if ($stmt->execute()) {
        $_SESSION["message"] = "Requisition successfully updated!";
    } else {
        $_SESSION["message"] = "Error updating requisition!";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to Head Office approval page
    header("Location: ../views/head_office.php");
    exit();
}
?>
