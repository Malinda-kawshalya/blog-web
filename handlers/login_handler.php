<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role_id'] = $user['role_id'];
        if ($user['role_id'] == 3) {
            header('Location: ../admin/dashboard.php');
        } elseif ($user['role_id'] == 2) {
            header('Location: ../blogger/dashboard.php');
        } else {
            header('Location: ../user/dashboard.php');
        }
    } else {
        header('Location: ../login.php?error=Invalid credentials');
    }
}
?>