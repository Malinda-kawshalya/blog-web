<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO comments (blog_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$blog_id, $user_id, $content]);
    header("Location: ../blog_view.php?id=$blog_id");
}
?>