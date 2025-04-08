<?php
require_once 'config.php';
require_once 'db.php';

// Clean input function
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Format date function
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

// Create excerpt function
function create_excerpt($text, $length = 150) {
    $text = strip_tags($text);
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= '...';
    }
    return $text;
}

// Check user authentication
function is_authenticated() {
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        return true;
    }
    return false;
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit;
}

// Upload image function
function upload_image($file) {
    $target_dir = "../uploads/";
    $filename = basename($file["name"]);
    $target_file = $target_dir . time() . '_' . $filename;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ['success' => false, 'message' => "File is not an image."];
    }
    
    // Check file size
    if ($file["size"] > 500000) {
        return ['success' => false, 'message' => "Sorry, your file is too large."];
    }
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        return ['success' => false, 'message' => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."];
    }
    
    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'file_path' => substr($target_file, 3)];
    } else {
        return ['success' => false, 'message' => "Sorry, there was an error uploading your file."];
    }
}