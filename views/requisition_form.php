<?php
require '../controllers/auth.php';
require '../config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <script>
        function addItem() {
            const container = document.getElementById("items-container");
            const div = document.createElement("div");
            div.innerHTML = `
                <input type="text" name="items[][name]" placeholder="Item Name" required>
                <input type="text" name="items[][unit]" placeholder="Unit of Issue" required>
                <input type="number" name="items[][quantity]" placeholder="Quantity" required>
                <input type="text" name="items[][remarks]" placeholder="Remarks">
                <button type="button" onclick="this.parentElement.remove()">Remove</button>
            `;
            container.appendChild(div);
        }
    </script>
    <title>Requisition Form</title>
</head>
<body>
    <h2>Requisition Request</h2>
    <form method="POST" action="../controllers/requisition.php">
        <label>Requisition Office:</label>
        <input type="text" name="office" required><br><br>

        <div id="items-container">
            <button type="button" onclick="addItem()">Add Item</button><br><br>
        </div>

        <button type="submit">Submit Request</button>
    </form>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
