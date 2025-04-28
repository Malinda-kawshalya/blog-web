<?php
require_once '../db.php';
require_once '../functions.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT b.*, u.username FROM blogs b JOIN users u ON b.user_id = u.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
$stmt->execute([$user_id]);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f7f9fc;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Navigation Styles */
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
            letter-spacing: 0.5px;
        }
        
        .nav-links {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
        }
        
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Header Section */
        .page-header {
            margin: 30px 0;
            text-align: center;
        }
        
        .page-title {
            font-size: 36px;
            font-weight: 700;
            color: #444;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            bottom: -10px;
            left: 20%;
            border-radius: 2px;
        }
        
        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 40px;
        }
        
        /* Blog Card */
        .blog-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .blog-card-content {
            padding: 25px;
        }
        
        .blog-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, #6e8efb, #a777e3);
        }
        
        .blog-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .blog-author {
            font-size: 14px;
            color: #888;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .blog-author::before {
            content: '✍️';
            margin-right: 5px;
        }
        
        .blog-excerpt {
            font-size: 15px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .blog-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-view {
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
            flex: 1;
            text-align: center;
        }
        
        .btn-edit {
            background: linear-gradient(90deg, #f8b500, #f7971e);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(247, 151, 30, 0.3);
            flex: 1;
            text-align: center;
        }
        
        .btn-view:hover, .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        .btn-edit:hover {
            box-shadow: 0 6px 15px rgba(247, 151, 30, 0.4);
        }
        
        /* Alert messages */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
            border-left: 4px solid;
        }
        
        .alert-success {
            background-color: rgba(72, 187, 120, 0.1);
            color: #38a169;
            border-color: #38a169;
        }
        
        .alert-error {
            background-color: rgba(245, 101, 101, 0.1);
            color: #e53e3e;
            border-color: #e53e3e;
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .empty-state-icon {
            font-size: 60px;
            margin-bottom: 20px;
            color: #a777e3;
        }
        
        .empty-state-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #444;
        }
        
        .empty-state-message {
            font-size: 16px;
            color: #777;
            margin-bottom: 20px;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">Blog Platform</a>
        <div class="nav-links">
            <a href="create_blog.php" class="btn">Create Blog</a>
            <a href="dashboard.php" class="btn">Dashboard</a>
            <a href="../user/logout.php" class="btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">My Blogs</h1>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (empty($blogs)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <h2 class="empty-state-title">No blogs yet</h2>
                <p class="empty-state-message">You haven't created any blogs. Get started by creating your first blog post.</p>
                <a href="create_blog.php" class="btn-view">Create Your First Blog</a>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card">
                        <div class="blog-card-content">
                            <h2 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
                            <p class="blog-author">By <?php echo htmlspecialchars($blog['username']); ?></p>
                            <p class="blog-excerpt"><?php echo substr(htmlspecialchars($blog['content']), 0, 100); ?>...</p>
                            <div class="blog-actions">
                                <a href="../blog_view.php?id=<?php echo $blog['id']; ?>" class="btn-view">View</a>
                                <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="btn-edit">Edit</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>