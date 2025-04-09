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

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage-posts.php');
    exit;
}

$post_id = (int)$_GET['id'];
$post = get_post($post_id);

// If post not found
if (!$post) {
    header('Location: manage-posts.php');
    exit;
}

$page_title = 'Edit Post';
$message = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean_input($_POST['title']);
    $content = $_POST['content']; // Don't clean HTML content
    $status = clean_input($_POST['status']);
    
    // Validate inputs
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Update post
        $result = update_post($post_id, $title, $content, $status);
        
        if ($result) {
            $message = 'Post updated successfully!';
            // Refresh post data
            $post = get_post($post_id);
        } else {
            $error = 'Failed to update post. Please try again.';
        }
    }
}

// Include custom admin header
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
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/c00hwde1hgbl8dfwjkdx6b6ty993x22g8jd150v7bpy5qmdl/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400
        });
    </script>
    <!-- Custom CSS -->
    <link href="<?php echo SITE_URL; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="index.php">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="create-post.php">
                                <i class="bi bi-file-earmark-plus me-2"></i>Create Post
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="manage-posts.php">
                                <i class="bi bi-file-earmark-text me-2"></i>Manage Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo SITE_URL; ?>" target="_blank">
                                <i class="bi bi-house me-2"></i>View Site
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Post</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="manage-posts.php" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Posts
                            </a>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo $post['title']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="12" required><?php echo $post['content']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="published" <?php echo $post['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                    <option value="draft" <?php echo $post['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                                <a href="<?php echo SITE_URL; ?>/single-post.php?id=<?php echo $post_id; ?>" target="_blank" class="btn btn-outline-info">View Post</a>
                            </div>
                        </form>
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