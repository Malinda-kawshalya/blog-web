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

// Delete post
$result = delete_post($post_id);

if ($result) {
    header('Location: manage-posts.php?message=Post deleted successfully!');
} else {
    header('Location: manage-posts.php?message=Failed to delete post.');
}
exit;