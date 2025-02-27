<?php
require '../config/db.php';
require 'auth.php';

if (!isLoggedIn() || $_SESSION["role"] !== "head_office") {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requisition_id = $_POST["requisition_id"];
    $recommendation = $_POST["recommendation"];
    $approver_name = $_SESSION["user_id"];
    $date = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("UPDATE requisitions SET head_office_approval = ?, head_office_name = ?, head_office_date = ? WHERE id = ?");
    $stmt->bind_param("sssi", $recommendation, $approver_name, $date, $requisition_id);

    if ($stmt->execute()) {
        echo "Requisition recommended successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
