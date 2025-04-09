<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Simple contact form processing
$page_title = 'Contact Us';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $subject = clean_input($_POST['subject']);
    $message_content = clean_input($_POST['message']);

    if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Here you would typically send an email or save to database
        // For this example, we'll just show a success message
        $message = 'Thank you for your message! We\'ll get back to you soon.';
        // Clear form fields
        $_POST = array();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_TITLE : SITE_TITLE; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-blue: #e7f1ff;
            --hover-blue: #0069d9;
            --accent-blue: #4dabf7;
            --shadow-blue: rgba(0, 123, 255, 0.15);
            --text-dark: #1a2b45;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 10%);
            background-size: 30px 30px;
            opacity: 0.3;
            z-index: -1;
        }

        .navbar {
            background: linear-gradient(45deg, var(--primary-blue), var(--dark-blue));
            box-shadow: 0 4px 25px var(--shadow-blue);
            padding: 1.2rem 0;
            position: relative;
            overflow: hidden;
        }

        .navbar::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(30deg);
            animation: shine 6s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(30deg); }
            50% { transform: translateX(100%) rotate(30deg); }
            100% { transform: translateX(-100%) rotate(30deg); }
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.7rem;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05) rotate(2deg);
            color: var(--light-blue) !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.95) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            color: white !important;
            background: var(--hover-blue);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--light-blue);
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 8px 30px var(--shadow-blue);
            padding: 2.5rem;
            margin: 2.5rem 0;
            position: relative;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .main-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--primary-blue), var(--accent-blue));
            border-radius: 22px;
            z-index: -1;
            opacity: 0.2;
            animation: gradientPulse 4s infinite;
        }

        @keyframes gradientPulse {
            0% { opacity: 0.2; }
            50% { opacity: 0.4; }
            100% { opacity: 0.2; }
        }

        .btn-custom {
            background: linear-gradient(45deg, var(--primary-blue), var(--accent-blue));
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-custom:hover {
            background: var(--dark-blue);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
            color: white;
        }

        .btn-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }

        .btn-custom:hover::after {
            width: 300px;
            height: 300px;
        }

        .contact-section {
            padding: 4rem 0;
        }

        .form-control, .form-control:focus {
            border-radius: 10px;
            border: 2px solid var(--light-blue);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .contact-info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px var(--shadow-blue);
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: var(--text-dark);
            position: relative;
        }

        h1::after, h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 60px;
            height: 3px;
            background: var(--primary-blue);
            transform: translateX(-50%);
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                    <?php echo SITE_TITLE; ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (is_authenticated()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn-custom ms-2" href="<?php echo SITE_URL; ?>/admin/logout.php">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link btn-custom" href="<?php echo SITE_URL; ?>/admin/login.php">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="main-container contact-section">
            <h1 class="text-center mb-5 fw-bold">Contact Us</h1>

            <?php if ($message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-7 mb-4">
                    <h2 class="fw-semibold mb-4">Send Us a Message</h2>
                    <form method="post" action="">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                placeholder="Your Name">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                placeholder="your.email@example.com">
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-medium">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required 
                                value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                placeholder="Message Subject">
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label fw-medium">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required 
                                placeholder="Your Message"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom">Send Message</button>
                    </form>
                </div>
                <div class="col-md-5 mb-4">
                    <h2 class="fw-semibold mb-4">Contact Information</h2>
                    <div class="contact-info-card">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <span class="fw-medium">Address:</span>
                            <p class="text-muted mb-0">123 Blog Street, Web City, 45678</p>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <span class="fw-medium">Email:</span>
                            <p class="text-muted mb-0">contact@<?php echo strtolower(str_replace(' ', '', SITE_TITLE)); ?>.com</p>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <span class="fw-medium">Phone:</span>
                            <p class="text-muted mb-0">+1 (555) 123-4567</p>
                        </div>
                        <div>
                            <h5 class="fw-semibold mb-3">Follow Us</h5>
                            <a href="#" class="text-primary me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                            <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-lg"></i></a>
                            <a href="#" class="text-primary"><i class="fab fa-instagram fa-lg"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-5 mt-5" style="background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3"><?php echo SITE_TITLE; ?></h5>
                    <p class="text-white opacity-75">A simple blog website built with PHP and Bootstrap.</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white opacity-75 text-decoration-none hover-link">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-white opacity-75 text-decoration-none hover-link">About</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-white opacity-75 text-decoration-none hover-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="text-white fw-bold mb-3">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-facebook-f me-2"></i>Facebook</a></li>
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-instagram me-2"></i>Instagram</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">
            <div class="text-center">
                <p class="text-white opacity-75 mb-0">© <?php echo date('Y'); ?> <?php echo SITE_TITLE; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
    <style>
        .hover-link {
            transition: all 0.3s ease;
            display: inline-block;
        }

        .hover-link:hover {
            color: white !important;
            opacity: 1 !important;
            transform: translateX(5px);
        }
    </style>
</body>
</html>