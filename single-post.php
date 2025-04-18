<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

$page_title = 'Post';
$error_message = '';

// Check if post ID exists in URL
if (isset($_GET['id'])) {
    $post_id = clean_input($_GET['id']);
    
    // Fetch post data
    $conn = db_connect();
    
    // Prepare the query with JOIN to get author username
    $sql = "SELECT p.*, u.username 
            FROM posts p
            JOIN users u ON p.author_id = u.id
            WHERE p.id = ? AND p.status = 'published'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $page_title = $post['title'];
    } else {
        $error_message = 'Post not found or not published';
    }
    
    // Fetch comments if post exists
    if (isset($post)) {
        $comment_sql = "SELECT c.*, u.username 
                        FROM comments c
                        JOIN users u ON c.user_id = u.id
                        WHERE c.post_id = ?
                        ORDER BY c.created_at DESC";
        $comment_stmt = $conn->prepare($comment_sql);
        $comment_stmt->bind_param("i", $post_id);
        $comment_stmt->execute();
        $comments_result = $comment_stmt->get_result();
        $comments = [];
    
        while ($comment = $comments_result->fetch_assoc()) {
            $comments[] = $comment;
        }
    
        $comment_stmt->close();
    }
}
    
    // Fetch categories
    $categories_sql = "SELECT name, COUNT(*) as post_count 
                     FROM categories 
                     GROUP BY name 
                     ORDER BY name ASC";
    
    $categories_result = $conn->query($categories_sql);
    $categories = [];
    
    if ($categories_result->num_rows > 0) {
        while ($category = $categories_result->fetch_assoc()) {
            $categories[] = $category;
        }
    }
    
    // Fetch recent posts
    $recent_sql = "SELECT id, title 
                 FROM posts 
                 WHERE status = 'published' 
                 ORDER BY created_at DESC 
                 LIMIT 5";
    
    $recent_result = $conn->query($recent_sql);
    $recent_posts = [];
    
    if ($recent_result->num_rows > 0) {
        while ($recent = $recent_result->fetch_assoc()) {
            $recent_posts[] = $recent;
        }
    }
    else {
        $error_message = 'No post specified';
    }
    $conn->close();
 


// Include header
require_once 'includes/header.php';
?>

<div class="container py-4">
    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php elseif (isset($post)): ?>
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <article>
                    <!-- Post header -->
                    <header class="mb-4">
                        <h1 class="fw-bolder mb-1"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="text-muted fst-italic mb-2">
                            Posted on <?php echo format_date($post['created_at']); ?> by 
                            <?php echo htmlspecialchars($post['username']); ?>
                        </div>
                        <?php if (isset($post['category'])): ?>
                            <div class="badge bg-secondary text-decoration-none link-light">
                                <?php echo htmlspecialchars($post['category']); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <!-- Post featured image -->
                    <?php if (!empty($post['image_url'])): ?>
                        <figure class="mb-4">
                            <img class="img-fluid rounded" 
                                 src="<?php echo htmlspecialchars($post['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </figure>
                    <?php endif; ?>
                    
                    <!-- Post content -->
                    <section class="mb-5">
                        <?php echo $post['content']; ?>
                    </section>
                </article>
                
                <!-- Comments section -->
                <section class="mb-5">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="mb-4">Leave a Comment</h4>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <!-- Comment form for logged in users -->
                                <form action="submit-comment.php" method="post" class="mb-4">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <div class="mb-3">
                                        <textarea class="form-control" name="content" rows="3" 
                                                  placeholder="Join the discussion and leave a comment!" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                                </form>
                            <?php else: ?>
                                <!-- Login prompt for guests -->
                                <div class="alert alert-info">
                                    Please <a href="login.php">login</a> to leave a comment.
                                </div>
                            <?php endif; ?>
                            
                            <!-- Comments display -->
                            <h4 class="mt-4">Comments (<?php echo count($comments); ?>)</h4>
                            
                            <?php if (empty($comments)): ?>
                                <p class="text-muted">No comments yet. Be the first to comment!</p>
                            <?php else: ?>
                                <?php foreach ($comments as $comment): ?>
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0">
                                            <img class="rounded-circle" src="https://via.placeholder.com/50" 
                                                 alt="<?php echo htmlspecialchars($comment['username']); ?>">
                                        </div>
                                        <div class="ms-3">
                                            <div class="fw-bold"><?php echo htmlspecialchars($comment['username']); ?></div>
                                            <div class="text-muted fst-italic mb-2">
                                                <?php echo format_date($comment['created_at']); ?>
                                            </div>
                                            <?php echo htmlspecialchars($comment['content']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
            
            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Search widget -->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <form action="search.php" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="text" name="q" 
                                       placeholder="Enter search term..." required>
                                <button class="btn btn-primary" type="submit">Go!</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Categories widget -->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <?php if (!empty($categories)): ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($categories as $category): ?>
                                    <li>
                                        <a href="category.php?name=<?php echo urlencode($category['name']); ?>">
                                            <?php echo htmlspecialchars($category['name']); ?> 
                                            (<?php echo $category['post_count']; ?>)
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No categories found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent posts widget -->
                <div class="card mb-4">
                    <div class="card-header">Recent Posts</div>
                    <div class="card-body">
                        <?php if (!empty($recent_posts)): ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($recent_posts as $recent): ?>
                                    <li>
                                        <a href="single-post.php?id=<?php echo $recent['id']; ?>">
                                            <?php echo htmlspecialchars($recent['title']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">No recent posts found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Include footer
require_once 'includes/footer.php';
?>