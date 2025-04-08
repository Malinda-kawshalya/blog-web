<?php
require_once 'config.php';

// Database connection function
function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Execute query function
function db_query($sql) {
    $conn = db_connect();
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

// Get posts function
function get_posts($limit = 10, $offset = 0) {
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.author_id = users.id 
            WHERE posts.status = 'published' 
            ORDER BY posts.created_at DESC 
            LIMIT $limit OFFSET $offset";
    
    $result = db_query($sql);
    $posts = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    
    return $posts;
}

// Get single post function
function get_post($post_id) {
    $post_id = (int)$post_id;
    $sql = "SELECT posts.*, users.username 
            FROM posts 
            JOIN users ON posts.author_id = users.id 
            WHERE posts.id = $post_id";
    
    $result = db_query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return false;
}

// Create post function
function create_post($title, $content, $author_id, $status = 'published') {
    $conn = db_connect();
    
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $status = $conn->real_escape_string($status);
    $author_id = (int)$author_id;
    
    $sql = "INSERT INTO posts (title, content, author_id, status, created_at) 
            VALUES ('$title', '$content', $author_id, '$status', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        $conn->close();
        return $last_id;
    } else {
        $conn->close();
        return false;
    }
}

// Update post function
function update_post($post_id, $title, $content, $status = 'published') {
    $conn = db_connect();
    
    $post_id = (int)$post_id;
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);
    $status = $conn->real_escape_string($status);
    
    $sql = "UPDATE posts 
            SET title = '$title', 
                content = '$content', 
                status = '$status', 
                updated_at = NOW() 
            WHERE id = $post_id";
    
    $result = $conn->query($sql);
    $conn->close();
    
    return $result;
}

// Delete post function
function delete_post($post_id) {
    $post_id = (int)$post_id;
    $sql = "DELETE FROM posts WHERE id = $post_id";
    
    return db_query($sql);
}

// Count posts function
function count_posts() {
    $sql = "SELECT COUNT(*) as total FROM posts WHERE status = 'published'";
    $result = db_query($sql);
    $row = $result->fetch_assoc();
    
    return $row['total'];
}

// User verification function
function verify_user($username, $password) {
    $conn = db_connect();
    
    $username = $conn->real_escape_string($username);
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $conn->close();
            return $user;
        }
    }
    
    $conn->close();
    return false;
}