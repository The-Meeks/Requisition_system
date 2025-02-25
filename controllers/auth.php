<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require '../config/db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function logout() {
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
