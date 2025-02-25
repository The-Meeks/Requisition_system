<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $office = $_POST["office"];
    $items = $_POST["items"];

    $stmt = $conn->prepare("INSERT INTO requisitions (user_id, office) VALUES (?, ?)");
    $stmt->bind_param("is", $_SESSION["user_id"], $office);
    $stmt->execute();
    $requisition_id = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO requisition_items (requisition_id, item_name, unit, quantity, remarks) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $stmt->bind_param("issis", $requisition_id, $item["name"], $item["unit"], $item["quantity"], $item["remarks"]);
        $stmt->execute();
    }

    header("Location: ../views/requisition_list.php");
}
?>
