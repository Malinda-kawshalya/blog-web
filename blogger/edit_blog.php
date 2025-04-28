<?php
require_once '../db.php';
require_once '../functions.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

$blog_id = $_GET['id'];
$blog = getBlogById($pdo, $blog_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_id = $_POST['blog_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // Verify the blog belongs to the user
    $stmt = $pdo->prepare("SELECT user_id FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch();

    if ($blog && $blog['user_id'] == $user_id) {
        $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$title, $content, $blog_id]);
        header('Location: ../blogger/my_blogs.php?success=Blog updated');
    } else {
        header('Location: ../blogger/my_blogs.php?error=Unauthorized');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
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
        
        /* Form Styles */
        .edit-form-container {
            max-width: 800px;
            margin: 40px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            position: relative;
        }
        
        .edit-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, #6e8efb, #a777e3);
        }
        
        .edit-form-header {
            padding: 25px 30px 15px;
            border-bottom: 1px solid #eee;
        }
        
        .edit-form-title {
            font-size: 28px;
            font-weight: 700;
            color: #444;
            position: relative;
            display: inline-block;
        }
        
        .edit-form-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }
        
        .edit-form-content {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 16px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            color: #333;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #6e8efb;
            box-shadow: 0 0 0 3px rgba(110, 142, 251, 0.2);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }
        
        .submit-btn {
            display: inline-block;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .edit-form-container {
                margin: 20px auto;
            }
            
            .edit-form-header, .edit-form-content {
                padding: 20px;
            }
            
            .edit-form-title {
                font-size: 24px;
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
        <div class="edit-form-container">
            <div class="edit-form-header">
                <h1 class="edit-form-title">Edit Your Blog</h1>
            </div>
            <div class="edit-form-content">
                <form action="" method="POST">
                    <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>">
                    <div class="form-group">
                        <label class="form-label">Blog Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Blog Content</label>
                        <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Update Blog</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>