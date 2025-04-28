<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="text-xl font-bold">Blog Platform</a>
        <div>
            <a href="manage_users.php" class="btn">Manage Users</a>
            <a href="manage_bloggers.php" class="btn ml-2">Manage Bloggers</a>
            <a href="manage_blogs.php" class="btn ml-2">Manage Blogs</a>
            <a href="../user/logout.php" class="btn ml-2">Logout</a>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
        <div class="card">
            <p>Welcome, Admin! Use the navigation to manage users, bloggers, and blogs.</p>
        </div>
    </div>
</body>
</html>