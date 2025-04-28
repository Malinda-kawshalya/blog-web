<?php
require_once '../db.php';
require_once '../functions.php';
require_once '../auth.php';

requireLogin();
requireRole(1);
$blogs = getBlogs($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="text-xl font-bold">Blog Platform</a>
        <div>
            <a href="profile.php" class="btn">Profile</a>
            <a href="logout.php" class="btn ml-2">Logout</a>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-6">User Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($blogs as $blog): ?>
                <div class="card">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($blog['title']); ?></h2>
                    <p class="text-gray-600">By <?php echo htmlspecialchars($blog['username']); ?></p>
                    <p class="mt-2"><?php echo substr($blog['content'], 0, 100); ?>...</p>
                    <a href="../blog_view.php?id=<?php echo $blog['id']; ?>" class="btn mt-4">Read More</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>