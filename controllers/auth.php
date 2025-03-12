<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if the user is not logged in
function check_auth() { 
    if (!isset($_SESSION["user_id"])) {
        $_SESSION["message"] = "Unauthorized access!";
        header("Location: ../views/login.php");
        exit();
    }
}

// Restrict access to specific roles
function check_role($allowed_roles) {
    if (!isset($_SESSION["role"]) || !in_array($_SESSION["role"], $allowed_roles)) {
        $_SESSION["message"] = "Unauthorized access!";
        header("Location: ../index.php");
        exit();
    }
}

// Logout function
function logout() { 
    session_unset();
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}
?>
