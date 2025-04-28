<?php
require_once 'db.php';
require_once 'functions.php';
require_once 'auth.php';

$blog_id = $_GET['id'];
$blog = getBlogById($pdo, $blog_id);
$comments = getComments($pdo, $blog_id);
$is_liked = isLoggedIn() ? hasLiked($pdo, $blog_id, $_SESSION['user_id']) : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9fc;
            margin: 0;
            padding: 0;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            padding: 15px 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar a.logo {
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, #6e8efb, #a777e3);
            border-radius: 6px 0 0 6px;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #444;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .text-gray {
            color: #888;
            margin-top: 5px;
            font-size: 14px;
        }

        textarea {
            width: 100%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            resize: vertical;
            font-size: 15px;
        }

        .btn-like {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 15px;
            font-weight: 600;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            transition: all 0.3s ease;
        }

        .btn-like:hover {
            transform: translateY(-2px);
        }

        .btn-like.liked {
            background: #555;
        }

        .comment {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .comment p {
            margin: 5px 0;
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">Blog Platform</a>
        <div class="nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="<?php echo $_SESSION['role_id'] == 2 ? 'blogger/dashboard.php' : 'user/dashboard.php'; ?>" class="btn">Dashboard</a>
                <a href="user/logout.php" class="btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn">Login</a>
                <a href="register.php" class="btn">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
            <p class="text-gray">By <?php echo htmlspecialchars($blog['username']); ?> on <?php echo $blog['created_at']; ?></p>
            <p style="margin-top: 20px;"><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>

            <?php if (isLoggedIn()): ?>
                <button onclick="likePost(<?php echo $blog_id; ?>)" class="btn-like <?php echo $is_liked ? 'liked' : ''; ?>">
                    <?php echo $is_liked ? 'Unlike' : 'Like'; ?>
                </button>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>Comments</h2>
            <?php if (isLoggedIn()): ?>
                <form action="handlers/comment_handler.php" method="POST">
                    <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
                    <textarea name="content" rows="4" required></textarea>
                    <button type="submit" class="btn-like" style="margin-top: 10px;">Add Comment</button>
                </form>
            <?php endif; ?>

            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p class="text-gray">By <?php echo htmlspecialchars($comment['username']); ?> on <?php echo $comment['created_at']; ?></p>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function likePost(blogId) {
            fetch('handlers/like_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'blog_id=' + blogId
            }).then(() => location.reload());
        }
    </script>
</body>
</html>
