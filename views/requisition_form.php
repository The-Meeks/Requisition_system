<?php 
session_start();
require '../config/db.php';

// Ensure user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["message"] = "Unauthorized access!";
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $office = $_POST["office"];
    $items = $_POST["items"];

    // Insert into requisitions table
    $stmt = $conn->prepare("INSERT INTO requisitions (user_id, office, status, created_at) VALUES (?, ?, 'Pending', NOW())");
    $stmt->bind_param("is", $user_id, $office);
    if ($stmt->execute()) {
        $requisition_id = $stmt->insert_id;

        // Insert multiple items into requisition_items table
        $stmt_items = $conn->prepare("INSERT INTO requisition_items (requisition_id, item_name, unit, quantity, remarks) VALUES (?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_items->bind_param("issis", $requisition_id, $item["name"], $item["unit"], $item["quantity"], $item["remarks"]);
            $stmt_items->execute();
        }

        $_SESSION["message"] = "Requisition submitted successfully!";
        header("Location: requisition_list.php");
        exit();
    } else {
        $error = "Error submitting requisition.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Requisition</title>
    <link rel="stylesheet" href="../public/styles.css">
    <script>
        function addItem() {
            const container = document.getElementById("items-container");
            const index = container.children.length;
            const html = `
                <div class="item-group">
                    <input type="text" name="items[${index}][name]" placeholder="Item Name" required>
                    <input type="text" name="items[${index}][unit]" placeholder="Unit" required>
                    <input type="number" name="items[${index}][quantity]" placeholder="Quantity" required>
                    <input type="text" name="items[${index}][remarks]" placeholder="Remarks">
                    <button type="button" onclick="removeItem(this)">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML("beforeend", html);
        }

        function removeItem(button) {
            button.parentElement.remove();
        }
    </script>
</head>
<body>
    <h2>New Requisition</h2>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <p style='color: green;'><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="office">Office:</label>
        <input type="text" id="office" name="office" required>

        <h3>Items</h3>
        <div id="items-container">
            <div class="item-group">
                <input type="text" name="items[0][name]" placeholder="Item Name" required>
                <input type="text" name="items[0][unit]" placeholder="Unit" required>
                <input type="number" name="items[0][quantity]" placeholder="Quantity" required>
                <input type="text" name="items[0][remarks]" placeholder="Remarks">
                <button type="button" onclick="removeItem(this)">Remove</button>
            </div>
        </div>

        <button type="button" onclick="addItem()">Add Item</button><br><br>
        <button type="submit">Submit Requisition</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
