<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';

    if ($post_id && $user_id && !empty($content)) {
        try {
            $conn = db_connect();
            $content = $conn->real_escape_string($content);

            // Insert the comment without the status field
            $sql = "INSERT INTO comments (post_id, user_id, content, created_at) 
                    VALUES ($post_id, $user_id, '$content', NOW())";

            if ($conn->query($sql) === TRUE) {
                $_SESSION['success_message'] = 'Your comment has been submitted.';
            } else {
                $_SESSION['error_message'] = 'An error occurred while submitting your comment: ' . $conn->error;
            }

            $conn->close();
        } catch (Exception $e) {
            error_log("Error submitting comment: " . $e->getMessage());
            $_SESSION['error_message'] = 'An error occurred while submitting your comment. Please try again later.';
        }
    } else {
        $_SESSION['error_message'] = 'Please fill in all required fields.';
    }

    header("Location: single-post.php?id=" . $post_id);
    exit;
}
?>