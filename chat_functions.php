<?php
// Database connection
function getDbConnection() {
    $host = 'localhost';
    $db = 'your_lms_database';
    $user = 'your_username';
    $pass = 'your_password';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// Create a new conversation
function createConversation($course_id, $student_id, $teacher_id) {
    $conn = getDbConnection();
    
    // Check if conversation already exists
    $stmt = $conn->prepare("SELECT conversation_id FROM chat_conversations 
                            WHERE course_id = ? AND student_id = ? AND teacher_id = ?");
    $stmt->execute([$course_id, $student_id, $teacher_id]);
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        return $row['conversation_id'];
    }
    
    // Create new conversation
    $stmt = $conn->prepare("INSERT INTO chat_conversations (course_id, student_id, teacher_id) 
                            VALUES (?, ?, ?)");
    $stmt->execute([$course_id, $student_id, $teacher_id]);
    
    return $conn->lastInsertId();
}

// Get all conversations for a user
function getUserConversations($user_id, $user_type) {
    $conn = getDbConnection();
    
    if ($user_type === 'student') {
        $stmt = $conn->prepare("SELECT cv.conversation_id, cv.course_id, c.course_name, 
                                t.teacher_name, cv.created_at
                                FROM chat_conversations cv
                                JOIN courses c ON cv.course_id = c.id
                                JOIN teachers t ON cv.teacher_id = t.id
                                WHERE cv.student_id = ?");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $conn->prepare("SELECT cv.conversation_id, cv.course_id, c.course_name, 
                                s.student_name, cv.created_at
                                FROM chat_conversations cv
                                JOIN courses c ON cv.course_id = c.id
                                JOIN students s ON cv.student_id = s.id
                                WHERE cv.teacher_id = ?");
        $stmt->execute([$user_id]);
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Save a message to the database
function saveMessage($conversation_id, $sender_id, $sender_type, $message) {
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("INSERT INTO chat_messages (conversation_id, sender_id, sender_type, message_text) 
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([$conversation_id, $sender_id, $sender_type, $message]);
    
    return $conn->lastInsertId();
}

// Get chat history for a conversation
function getChatHistory($conversation_id) {
    $conn = getDbConnection();
    
    $stmt = $conn->prepare("SELECT message_id, sender_id, sender_type, message_text, sent_at, is_read 
                            FROM chat_messages
                            WHERE conversation_id = ?
                            ORDER BY sent_at ASC");
    $stmt->execute([$conversation_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Mark messages as read
function markMessagesAsRead($conversation_id, $user_id, $user_type) {
    $conn = getDbConnection();
    
    $opposite_type = ($user_type === 'student') ? 'teacher' : 'student';
    
    $stmt = $conn->prepare("UPDATE chat_messages 
                            SET is_read = 1
                            WHERE conversation_id = ? AND sender_type = ? AND is_read = 0");
    $stmt->execute([$conversation_id, $opposite_type]);
    
    return $stmt->rowCount();
}