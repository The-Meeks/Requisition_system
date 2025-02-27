<?php
require '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $office = $_POST["office"];
    $items = $_POST["items"];

    $stmt = $conn->prepare("INSERT INTO requisitions (user_id, office, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("is", $user_id, $office);
    $stmt->execute();
    $requisition_id = $stmt->insert_id;

    foreach ($items as $item) {
        $stmt = $conn->prepare("INSERT INTO requisition_items (requisition_id, item_name, unit, quantity, remarks) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $requisition_id, $item["name"], $item["unit"], $item["quantity"], $item["remarks"]);
        $stmt->execute();
    }

    header("Location: ../views/requisition_list.php");
}
?>
