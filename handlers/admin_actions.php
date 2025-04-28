<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(3);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete Blog
    if (isset($_POST['delete_blog'])) {
        $blog_id = $_POST['blog_id'];
        $stmt = $pdo->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->execute([$blog_id]);
        header('Location: ../admin/manage_blogs.php');
    }
    // Delete User
    elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        // Prevent admins from deleting themselves
        if ($user_id != $_SESSION['user_id']) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            header('Location: ../admin/manage_users.php');
        } else {
            header('Location: ../admin/manage_users.php?error=Cannot delete yourself');
        }
    }
    // Update User Role
    elseif (isset($_POST['update_role'])) {
        $user_id = $_POST['user_id'];
        $role_id = $_POST['role_id'];
        $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        $stmt->execute([$role_id, $user_id]);
        header('Location: ../admin/manage_users.php');
    }
}
?>