<?php
require '../config/db.php'; // Database connection

session_start(); // Start session for messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
    $role = $_POST['role']; // Get selected role

    // Check if email or username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Redirect to login page if duplicate email or username is found
        $_SESSION['error'] = "This email or username is already registered. Please log in.";
        header("Location: ../views/login.php");
        exit();
    } else {
        // Insert new user with role
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $password, $role);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! You can now log in.";
            header("Location: ../views/login.php"); // Redirect to login page
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header("Location: ../views/register.php");
            exit();
        }
    }

    $stmt->close();
    $conn->close();
}
?>
