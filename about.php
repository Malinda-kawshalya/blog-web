<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'About Us';
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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            margin-left: 40 px;
        }

        .navbar {
            background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));
            box-shadow: 0 2px 15px rgba(0, 123, 255, 0.3);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: white !important;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            color: white !important;
            background: var(--hover-blue);
        }

        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 123, 255, 0.1);
            padding: 2rem;
            margin: 2rem 0;
        }

        .btn-custom {
            background: var(--primary-blue);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 25px;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .about-section {
            padding: 3rem 0;
        }

        .team-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 123, 255, 0.2);
        }

        .team-img {
            height: 200px;
            object-fit: cover;
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
                            <a class="nav-link active" href="<?php echo SITE_URL; ?>/about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
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
        <div class="main-container about-section">
            <h1 class="text-center mb-5 fw-bold text-dark">About <?php echo SITE_TITLE; ?></h1>
            
            <div class="row mb-5">
                <div class="col-md-6 mb-4">
                    <h2 class="fw-semibold text-dark">Our Story</h2>
                    <p class="text-muted">
                        <?php echo SITE_TITLE; ?> is a platform dedicated to sharing knowledge and insights through quality content. 
                        Founded in <?php echo date('Y'); ?>, we aim to provide a space where ideas flourish and readers find valuable information.
                        Our team is passionate about creating engaging content that informs and inspires.
                    </p>
                </div>
                <div class="col-md-6 mb-4">
                    <h2 class="fw-semibold text-dark">Our Mission</h2>
                    <p class="text-muted">
                        Our mission is to empower our community with well-crafted articles and resources. 
                        We strive to maintain authenticity, creativity, and excellence in everything we publish, 
                        making <?php echo SITE_TITLE; ?> a trusted source for information and inspiration.
                    </p>
                </div>
            </div>

            <h2 class="text-center mb-4 fw-bold text-dark">Meet Our Team</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="team-card bg-white">
                        <img src="<?php echo SITE_URL; ?>/assets/images/team1.jpg" class="w-100 team-img" alt="Team Member">
                        <div class="p-3">
                            <h5 class="fw-semibold">John Doe</h5>
                            <p class="text-muted small">Founder & Editor</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="team-card bg-white">
                        <img src="<?php echo SITE_URL; ?>/assets/images/team2.jpg" class="w-100 team-img" alt="Team Member">
                        <div class="p-3">
                            <h5 class="fw-semibold">Jane Smith</h5>
                            <p class="text-muted small">Content Creator</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="team-card bg-white">
                        <img src="<?php echo SITE_URL; ?>/assets/images/team3.jpg" class="w-100 team-img" alt="Team Member">
                        <div class="p-3">
                            <h5 class="fw-semibold">Mike Johnson</h5>
                            <p class="text-muted small">Web Developer</p>
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