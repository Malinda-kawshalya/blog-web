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

$page_title = 'Create New Post';
$message = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean_input($_POST['title']);
    $content = $_POST['content'];
    $status = clean_input($_POST['status']);
    
    error_log("Form submitted: Title = " . $title);
    error_log("Content length: " . strlen($content));
    error_log("User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));
    
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            $post_id = create_post($title, $content, $_SESSION['user_id'], $status);
            if ($post_id) {
                $message = 'Post created successfully! Post ID: ' . $post_id;
                error_log("Success: Post created with ID " . $post_id);
            } else {
                $error = 'Failed to create post. Please try again.';
                error_log("Error: Failed to create post");
            }
        } catch (Exception $e) {
            $error = 'An error occurred while creating the post.';
            error_log("Exception: " . $e->getMessage());
        }
    }
}
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
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/c00hwde1hgbl8dfwjkdx6b6ty993x22g8jd150v7bpy5qmdl/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
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

        .btn-custom {
            background: var(--primary-blue);
            border: none;
            border-radius: 25px;
            padding: 0.7rem 1.5rem;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--light-blue);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: var(--primary-blue);
            color: white;
            border-radius: 15px 15px 0 0;
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
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="create-post.php">
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
                    <h1 class="h2 fw-bold text-dark">Create New Post</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="manage-posts.php" class="btn btn-custom">
                            <i class="bi bi-arrow-left me-2"></i>Back to Posts
                        </a>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark-plus me-2"></i>New Post
                    </div>
                    <div class="card-body">
                        <form method="post" action="" id="post-form">
                            <div class="mb-4">
                                <label for="title" class="form-label fw-medium">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter post title">
                            </div>
                            <div class="mb-4">
                                <label for="content" class="form-label fw-medium">Content</label>
                                <textarea class="form-control" id="content" name="content" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="status" class="form-label fw-medium">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="published" selected>Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-custom">
                                <i class="bi bi-plus-lg me-2"></i>Create Post
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TinyMCE Initialization -->
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400,
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
                editor.on('init', function() {
                    editor.getContainer().style.borderRadius = '10px';
                    editor.getContainer().style.border = '2px solid var(--light-blue)';
                });
            }
        });

        document.getElementById('post-form').addEventListener('submit', function(e) {
            tinymce.triggerSave();
            var content = document.getElementById('content').value;
            if (!content || content.trim() === '') {
                e.preventDefault();
                alert('The content field cannot be empty.');
            }
        });
    </script>
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>