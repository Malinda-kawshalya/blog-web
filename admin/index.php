<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/db.php';

// Check if user is authenticated
if (!is_authenticated()) {
    header('Location: login.php');
    exit;
}

$page_title = 'Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title . ' - ' . SITE_TITLE; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-blue: #e7f1ff;
            --hover-blue: #0069d9;
            --sidebar-bg: #1a2b45;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }

        .sidebar {
            background: var(--sidebar-bg);
            height: 100vh;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.8rem 1.5rem !important;
            border-radius: 8px;
            margin: 0.3rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-blue);
            color: white !important;
            transform: translateX(5px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.2);
        }

        .bg-primary {
            background: linear-gradient(45deg, var(--primary-blue), var(--dark-blue)) !important;
        }

        .bg-success {
            background: linear-gradient(45deg, #28a745, #218838) !important;
        }

        .bg-warning {
            background: linear-gradient(45deg, #ffc107, #e0a800) !important;
        }

        .btn-custom {
            background: var(--primary-blue);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.2rem;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            color: white;
        }

        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .table thead {
            background: var(--light-blue);
            color: var(--dark-blue);
        }

        .table-hover tbody tr:hover {
            background: var(--light-blue);
        }

        .card-header {
            background: var(--primary-blue);
            color: white;
            border-radius: 15px 15px 0 0 !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create-post.php">
                                <i class="bi bi-file-earmark-plus me-2"></i>Create Post
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage-posts.php">
                                <i class="bi bi-file-earmark-text me-2"></i>Manage Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>" target="_blank">
                                <i class="bi bi-house me-2"></i>View Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <h1 class="h2 fw-bold text-dark">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="create-post.php" class="btn btn-custom">
                            <i class="bi bi-plus-lg me-2"></i>New Post
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title opacity-75">Total Posts</h5>
                                        <h2 class="display-4 fw-bold"><?php echo count_posts(); ?></h2>
                                    </div>
                                    <i class="bi bi-file-earmark-text display-4 opacity-75"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 d-flex align-items-center justify-content-between">
                                <a href="manage-posts.php" class="text-white text-decoration-none">View Details</a>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title opacity-75">Comments</h5>
                                        <h2 class="display-4 fw-bold">12</h2>
                                    </div>
                                    <i class="bi bi-chat-left-text display-4 opacity-75"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 d-flex align-items-center justify-content-between">
                                <a href="#" class="text-white text-decoration-none">View Details</a>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title opacity-75">Users</h5>
                                        <h2 class="display-4 fw-bold">3</h2>
                                    </div>
                                    <i class="bi bi-people display-4 opacity-75"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 d-flex align-items-center justify-content-between">
                                <a href="#" class="text-white text-decoration-none">View Details</a>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-table me-2"></i>Recent Posts
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="py-3">ID</th>
                                    <th class="py-3">Title</th>
                                    <th class="py-3">Author</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_posts = get_posts(5);
                                foreach ($recent_posts as $post):
                                ?>
                                <tr>
                                    <td class="py-3"><?php echo $post['id']; ?></td>
                                    <td class="py-3"><?php echo $post['title']; ?></td>
                                    <td class="py-3"><?php echo $post['username']; ?></td>
                                    <td class="py-3"><?php echo format_date($post['created_at']); ?></td>
                                    <td class="py-3">
                                        <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-custom me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?php echo SITE_URL; ?>/single-post.php?id=<?php echo $post['id']; ?>" target="_blank" class="btn btn-sm btn-info me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>