<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Verify the blog belongs to the user
    $stmt = $pdo->prepare("SELECT user_id FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch();

    if ($blog && $blog['user_id'] == $user_id) {
        $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$title, $content, $blog_id]);
        header('Location: ../blogger/my_blogs.php?success=Blog updated');
    } else {
        header('Location: ../blogger/my_blogs.php?error=Unauthorized');
    }
}
?>