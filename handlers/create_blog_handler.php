<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO blogs (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $user_id]);
        header('Location: ../blogger/my_blogs.php?success=Blog created successfully');
    } catch (PDOException $e) {
        header('Location: ../blogger/create_blog.php?error=Failed to create blog');
    }
}
?>