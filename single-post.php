<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

$page_title = 'Home';

// Pagination
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Get posts
$posts = get_posts($posts_per_page, $offset);
$total_posts = count_posts();
$total_pages = ceil($total_posts / $posts_per_page);

include 'includes/header.php';
?>

<div class="row">
    <div class="col-lg-8">
        <h1 class="mb-4">Latest Blog Posts</h1>
        
        <?php if (empty($posts)): ?>
            <div class="alert alert-info">No posts found.</div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">
                            <a href="single-post.php?id=<?php echo $post['id']; ?>" class="text-decoration-none"><?php echo $post['title']; ?></a>
                        </h2>
                        <p class="card-text text-muted">
                            <small>Posted by <?php echo $post['username']; ?> on <?php echo format_date($post['created_at']); ?></small>
                        </p>
                        <div class="card-text mb-3">
                            <?php echo create_excerpt($post['content']); ?>
                        </div>
                        <a href="single-post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                </article>
            <?php endforeach; ?>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">About</div>
            <div class="card-body">
                <p>Welcome to our blog! Here you'll find interesting articles and useful information.</p>
                <a href="about.php" class="btn btn-outline-primary">Learn More</a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Categories</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-decoration-none">Technology</a>
                        <span class="badge bg-primary rounded-pill">12</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-decoration-none">Lifestyle</a>
                        <span class="badge bg-primary rounded-pill">8</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-decoration-none">Travel</a>
                        <span class="badge bg-primary rounded-pill">5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="text-decoration-none">Food</a>
                        <span class="badge bg-primary rounded-pill">3</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>