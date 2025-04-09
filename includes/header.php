<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
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

        .navbar-toggler {
            border: none;
            color: white;
        }

        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 123, 255, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        /* Custom button styles */
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
        footer {
    position: relative;
    overflow: hidden;
}

.hover-link {
    transition: all 0.3s ease;
    display: inline-block;
}

.hover-link:hover {
    color: white !important;
    opacity: 1 !important;
    transform: translateX(5px);
}

footer:before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: rgba(255, 255, 255, 0.05);
    transform: rotate(30deg);
    pointer-events: none;
}

/* Social media icons styling */
.fab {
    transition: all 0.3s ease;
}

.hover-link:hover .fab {
    color: #fff;
    transform: scale(1.2);
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
        <div class="main-container">