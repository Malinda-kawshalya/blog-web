<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

// Check if user is authenticated
if (!is_authenticated()) {
    header('Location: login.php');
    exit;
}

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage-posts.php');
    exit;
}

$post_id = (int)$_GET['id'];

// Update post status to published
$conn = db_connect();
$sql = "UPDATE posts SET status = 'published' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $post_id);

if ($stmt->execute()) {
    $message = "Post published successfully.";
} else {
    $message = "Error publishing post: " . $conn->error;
}

$stmt->close();
$conn->close();

// Redirect back to manage posts with message
header('Location: manage-posts.php?message=' . urlencode($message));
exit;