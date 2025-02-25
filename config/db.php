<?php
$host = "localhost";
$user = "root";  // Change if needed
$password = "";  // Leave blank if using XAMPP
$database = "beverage_requisition";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
