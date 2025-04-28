<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(2);

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->execute([$username, $email, $user_id]);
    header('Location: profile.php?success=Profile updated');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Profile</title>
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
        
        /* Profile Card Styles */
        .profile-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            max-width: 500px;
            margin: 50px auto;
            padding: 0;
            overflow: hidden;
            position: relative;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            padding: 30px 30px 40px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .profile-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .profile-subtitle {
            font-size: 15px;
            opacity: 0.9;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .profile-avatar span {
            font-size: 40px;
            font-weight: 600;
            color: #a777e3;
        }
        
        .profile-content {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
            font-size: 15px;
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
        
        .submit-btn {
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            border: none;
            width: 100%;
            padding: 12px;
            font-weight: 500;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        .success-message {
            background-color: rgba(72, 187, 120, 0.1);
            color: #38a169;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: center;
            border-left: 4px solid #38a169;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .profile-card {
                margin: 20px auto;
            }
            
            .profile-header {
                padding: 20px 20px 30px;
            }
            
            .profile-content {
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
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <span><?php echo strtoupper(substr($user['username'], 0, 1)); ?></span>
                </div>
                <h1 class="profile-title">Blogger Profile</h1>
                <p class="profile-subtitle">Manage your account information</p>
            </div>
            
            <div class="profile-content">
                <?php if (isset($_GET['success'])): ?>
                    <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
                <?php endif; ?>
                
                <form action="profile.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-input" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>