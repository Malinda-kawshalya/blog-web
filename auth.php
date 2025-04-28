<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role_id'] ?? null;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit;
    }
}

function requireRole($role_id) {
    requireLogin();
    if (getUserRole() != $role_id) {
        header('Location: ../index.php');
        exit;
    }
}
?>