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

$page_title = 'Manage Posts';
$message = '';

// Handle message from redirect
if (isset($_GET['message'])) {
    $message = clean_input($_GET['message']);
}

// Get all posts
$conn = db_connect();
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.author_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
$conn->close();

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
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
                    <h1 class="h2">Manage Posts</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="create-post.php" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-lg"></i> New Post
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

                <div class="card">
                    <div class="card-body">
                        <table id="postsTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?php echo $post['id']; ?></td>
                                    <td><?php echo $post['title']; ?></td>
                                    <td><?php echo $post['username']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $post['status'] === 'published' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo ucfirst($post['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo format_date($post['created_at']); ?></td>
                                    <td>
    <a href="edit-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <a href="<?php echo SITE_URL; ?>/single-post.php?id=<?php echo $post['id']; ?>" target="_blank" class="btn btn-sm btn-info" title="View">
        <i class="bi bi-eye"></i>
    </a>
    <?php if ($post['status'] === 'draft'): ?>
    <a href="publish-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-success" title="Publish" onclick="return confirm('Are you sure you want to publish this post?')">
        <i class="bi bi-arrow-up-square"></i>
    </a>
    <?php endif; ?>
    <a href="delete-post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this post?')">
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#postsTable').DataTable({
                order: [[0, 'desc']]
            });
        });
    </script>
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>