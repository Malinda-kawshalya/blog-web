<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['user_id'];

    if (hasLiked($pdo, $blog_id, $user_id)) {
        $stmt = $pdo->prepare("DELETE FROM likes WHERE blog_id = ? AND user_id = ?");
        $stmt->execute([$blog_id, $user_id]);
        echo json_encode(['success' => true, 'message' => 'Unliked']);
    } else {
        $stmt = $pdo->prepare("INSERT INTO likes (blog_id, user_id) VALUES (?, ?)");
        $stmt->execute([$blog_id, $user_id]);
        echo json_encode(['success' => true, 'message' => 'Liked']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
function hasLiked($pdo, $blog_id, $user_id) {
    $stmt = $pdo->prepare("SELECT 1 FROM likes WHERE blog_id = ? AND user_id = ?");
    $stmt->execute([$blog_id, $user_id]);
    return $stmt->fetch() !== false;
}
?>