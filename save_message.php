<?php
require_once 'chat_functions.php';

// Get POST parameters
$conversation_id = $_POST['conversation_id'] ?? 0;
$sender_id = $_POST['sender_id'] ?? 0;
$sender_type = $_POST['sender_type'] ?? '';
$message = $_POST['message'] ?? '';

// Validate inputs
if (!$conversation_id || !$sender_id || !$sender_type || !$message) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Save message to database
$message_id = saveMessage($conversation_id, $sender_id, $sender_type, $message);

echo json_encode(['success' => true, 'message_id' => $message_id]);