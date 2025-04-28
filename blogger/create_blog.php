<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

// Blog creation handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO blogs (title, content, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $user_id]);
        header('Location: my_blogs.php?success=Blog created successfully');
        exit;
    } catch (PDOException $e) {
        $error = "Failed to create blog";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog</title>
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
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Create Blog Form Card */
        .create-blog-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 50px auto;
            padding: 0;
            overflow: hidden;
            position: relative;
        }
        
        .card-header {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            padding: 25px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .card-title {
            font-size: 26px;
            font-weight: 600;
            position: relative;
            display: inline-block;
        }
        
        .card-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            bottom: -8px;
            left: 20%;
            border-radius: 2px;
        }
        
        .card-icon {
            font-size: 24px;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .card-content {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 10px;
            color: #555;
            font-size: 16px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #a777e3;
            box-shadow: 0 0 0 3px rgba(167, 119, 227, 0.2);
        }
        
        .form-textarea {
            min-height: 200px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            border: none;
            width: 100%;
            padding: 14px;
            font-weight: 500;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        /* Form Tips */
        .form-tips {
            background-color: rgba(110, 142, 251, 0.1);
            border-left: 4px solid #6e8efb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .form-tips h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #6e8efb;
        }
        
        .form-tips p {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
        }
        
        .form-tips ul {
            list-style-type: none;
            padding-left: 10px;
        }
        
        .form-tips li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }
        
        .form-tips li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #6e8efb;
            font-weight: bold;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .create-blog-card {
                margin: 20px auto;
            }
            
            .card-header {
                padding: 20px 20px;
            }
            
            .card-content {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="logo">Blog Platform</a>
        <div class="nav-links">
            <a href="dashboard.php" class="btn">Dashboard</a>
            <a href="my_blogs.php" class="btn">My Blogs</a>
            <a href="../user/logout.php" class="btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="create-blog-card">
            <div class="card-header">
                <h1 class="card-title"><span class="card-icon">✏️</span>Create Blog</h1>
            </div>
            
            <div class="card-content">
                <div class="form-tips">
                    <h3>Tips for a Great Blog Post</h3>
                    <p>Make your blog post engaging and easy to read:</p>
                    <ul>
                        <li>Use a clear, descriptive title</li>
                        <li>Break content into paragraphs</li>
                        <li>Include relevant examples</li>
                        <li>Proofread before publishing</li>
                    </ul>
                </div>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-input" placeholder="Enter an attention-grabbing title" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-input form-textarea" placeholder="Write your blog content here..." required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Publish Blog</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>