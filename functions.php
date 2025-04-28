<?php
require_once 'db.php';

function getBlogs($pdo, $limit = 10) {
    $limit = (int)$limit; // Ensure $limit is an integer to prevent SQL injection
    $stmt = $pdo->prepare("SELECT b.*, u.username FROM blogs b JOIN users u ON b.user_id = u.id WHERE b.is_published = 1 ORDER BY b.created_at DESC LIMIT $limit");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getBlogById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT b.*, u.username FROM blogs b JOIN users u ON b.user_id = u.id WHERE b.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getComments($pdo, $blog_id) {
    $stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.blog_id = ? ORDER BY c.created_at DESC");
    $stmt->execute([$blog_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function hasLiked($pdo, $blog_id, $user_id) {
    $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE blog_id = ? AND user_id = ?");
    $stmt->execute([$blog_id, $user_id]);
    return $stmt->fetch() !== false;
}
?>