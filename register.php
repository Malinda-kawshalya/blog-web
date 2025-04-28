<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Blog Platform</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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
        
        .nav-btn {
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
        }
        
        .nav-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Main Content - FIXED CENTERING */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0;
        }
        
        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            padding: 35px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, #6e8efb, #a777e3);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Form Styles */
        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #444;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 4px;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            bottom: -8px;
            left: 0;
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #a777e3;
            box-shadow: 0 0 0 3px rgba(167, 119, 227, 0.2);
        }
        
        /* Button Styles */
        .btn {
            display: inline-block;
            background: linear-gradient(90deg, #6e8efb, #a777e3);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
            cursor: pointer;
            border: none;
            text-align: center;
            width: 100%;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(110, 142, 251, 0.4);
        }
        
        /* Link Styles */
        .text-link {
            color: #6e8efb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .text-link:hover {
            color: #a777e3;
            text-decoration: underline;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Footer */
        .footer {
            background: #333;
            color: white;
            padding: 40px 0 20px;
            margin-top: auto; /* Push footer to bottom */
            border-top: 5px solid #6e8efb;
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
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            
            .card {
                max-width: 95%;
                padding: 25px;
            }
            
            .footer-section {
                flex: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="index.php" class="logo">Blog Platform</a>
        <div class="nav-links">
            <a href="login.php" class="btn">Login</a>
            
        </div>
    </nav>
    
    <!-- Main Content - Now properly centered -->
    <div class="main-content">
        <div class="card">
            <h1 class="form-title">Create an Account</h1>
            <form action="handlers/register_handler.php" method="POST">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role_id" class="form-control">
                        <option value="1">Reader</option>
                        <option value="2">Blogger</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <p class="text-center" style="margin-top: 20px;">
                Already have an account? <a href="login.php" class="text-link">Login</a>
            </p>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3 class="footer-title">About Us</h3>
                <p>A platform for writers and readers to connect through meaningful content.</p>
            </div>
            
            <div class="footer-section">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
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
</body>
</html>