<?php
require_once '../db.php';
require_once '../functions.php';
require_once '../auth.php';

requireLogin();
requireRole(2);
$blogs = getBlogs($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Dashboard</title>
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
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Full height of viewport */
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1 0 auto; /* Takes up all available space */
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
            width: 100%;
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
            flex-wrap: wrap;
            justify-content: flex-end;
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
            white-space: nowrap;
        }
        
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Header Section */
        .dashboard-header {
            margin: 30px 0;
            text-align: center;
        }
        
        .dashboard-title {
            font-size: 36px;
            font-weight: 700;
            color: #444;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .dashboard-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            bottom: -10px;
            left: 20%;
            border-radius: 2px;
        }
        
        /* Main Content Wrapper */
        .main-content {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 40px;
            width: 100%;
        }
        
        /* Blog Card */
        .blog-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .blog-card-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
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
            flex: 1;
        }
        
        .read-more {
            display: inline-block;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
            align-self: flex-start;
            margin-top: auto;
        }
        
        .read-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
            border-top: 5px solid #6e8efb;
            width: 100%;
            flex-shrink: 0; /* Prevents footer from shrinking */
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 200px;
            margin-bottom: 30px;
            padding: 0 15px;
        }
        
        .footer-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background: #6e8efb;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #6e8efb;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #444;
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 20px;
            padding-right: 20px;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-links {
                justify-content: center;
                width: 100%;
                margin-top: 10px;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .dashboard-title {
                font-size: 28px;
            }
            
            .footer-section {
                flex: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .nav-links {
                flex-direction: column;
                align-items: center;
                gap: 8px;
            }
            
            .btn {
                width: 100%;
                text-align: center;
            }
            
            .dashboard-title::after {
                width: 80%;
                left: 10%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">Blog Platform</a>
        <div class="nav-links">
            <a href="create_blog.php" class="btn">Create Blog</a>
            <a href="my_blogs.php" class="btn">My Blogs</a>
            <a href="profile.php" class="btn">Profile</a>
            <a href="../user/logout.php" class="btn">Logout</a>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="container">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Blogger Dashboard</h1>
            </div>
            
            <div class="blog-grid">
                <?php foreach ($blogs as $blog): ?>
                    <div class="blog-card">
                        <div class="blog-card-content">
                            <h2 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h2>
                            <p class="blog-author">By <?php echo htmlspecialchars($blog['username']); ?></p>
                            <p class="blog-excerpt"><?php echo substr(htmlspecialchars($blog['content']), 0, 100); ?>...</p>
                            <a href="../blog_view.php?id=<?php echo $blog['id']; ?>" class="read-more">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-title">About Us</h3>
                    <p>A platform for writers and readers to connect through meaningful content.</p>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../login.php">Login</a></li>
                        <li><a href="../register.php">Register</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3 class="footer-title">Contact</h3>
                    <ul class="footer-links">
                        <li>Email: info@blogplatform.com</li>
                        <li>Phone: (123) 456-7890</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Blog Platform. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>