<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if (getUserRole() !== $role) {
        die("Access Denied. You do not have permission to view this page.");
    }
}
?>
