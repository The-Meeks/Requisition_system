<?php
session_start();
require '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Unauthorized access!";
    header("Location: ../views/login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $office = $_POST["office"];
    $items = $_POST["items"];

    // Validate input
    if (empty($office) || empty($items)) {
        $_SESSION["message"] = "All fields are required!";
        header("Location: ../views/requisition_form.php");
        exit();
    }

    // Insert into `requisitions` table
    $stmt = $conn->prepare("INSERT INTO requisitions (user_id, office, status, created_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("is", $user_id, $office);
    
    if ($stmt->execute()) {
        $requisition_id = $stmt->insert_id;

        // Insert multiple items into `requisition_items` table
        $stmt_items = $conn->prepare("INSERT INTO requisition_items (requisition_id, item_name, unit, quantity, remarks) VALUES (?, ?, ?, ?, ?)");

        foreach ($items as $item) {
            if (!empty($item["name"]) && !empty($item["unit"]) && !empty($item["quantity"])) {
                $stmt_items->bind_param("issis", $requisition_id, $item["name"], $item["unit"], $item["quantity"], $item["remarks"]);
                $stmt_items->execute();
            }
        }

        $_SESSION["message"] = "Requisition submitted successfully!";
        header("Location: ../views/requisition_list.php");
        exit();
    } else {
        $_SESSION["message"] = "Error submitting requisition.";
        header("Location: ../views/requisition_form.php");
        exit();
    }
}
?>
