<?php
require_once '../db.php';
require_once '../auth.php';

requireLogin();
requireRole(3);

$stmt = $pdo->prepare("
    SELECT b.*, u.username, c.name AS category_name 
    FROM blogs b 
    JOIN users u ON b.user_id = u.id 
    JOIN categories c ON b.category_id = c.id 
    ORDER BY b.created_at DESC
");
$stmt->execute();
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="../index.php" class="navbar-brand">Blog Platform</a>
        <div class="navbar-links">
            <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
            <a href="../user/logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>Manage Blogs</h1>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($blogs as $blog): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($blog['title']); ?></td>
                            <td><?php echo htmlspecialchars($blog['category_name']); ?></td>
                            <td><?php echo htmlspecialchars($blog['username']); ?></td>
                            <td>
                                <form action="../handlers/admin_actions.php" method="POST">
                                    <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                                    <button type="submit" name="delete_blog" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>