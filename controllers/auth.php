<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../config/db.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

/**
 * Check if user has a specific role
 */
function hasRole($role) {
    return isset($_SESSION["role"]) && $_SESSION["role"] === $role;
}

/**
 * Redirect user to login page if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../views/login.php");
        exit();
    }
}

/**
 * Logout function
 */
function logout() {
    session_unset(); // Remove session variables
    session_destroy(); // Destroy session
    header("Location: ../index.php");
    exit();
}
?>
