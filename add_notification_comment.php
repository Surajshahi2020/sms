<?php
session_start();
include 'config/dbcon.php';

// Ensure user is logged in
if (!isset($_SESSION['auth_user'])) {
    header("Location: login.php");
    exit();
}

// Check required POST data
if (isset($_POST['comment'], $_POST['notification_id'])) {
    $comment = trim($_POST['comment']);
    $notification_id = intval($_POST['notification_id']);
    $user_id = $_SESSION['auth_user']['user_id'];

    // Optional parent comment
    $parent_comment_id = isset($_POST['parent_comment_id']) && is_numeric($_POST['parent_comment_id']) 
                         ? intval($_POST['parent_comment_id']) 
                         : null;

    if ($comment !== '') {
        if ($parent_comment_id === null) {
            // Insert without parent_comment_id
            $stmt = $con->prepare("
                INSERT INTO notification_comments 
                    (notification_id, user_id, comment, created_at) 
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->bind_param("iis", $notification_id, $user_id, $comment);
        } else {
            // Insert with parent_comment_id
            $stmt = $con->prepare("
                INSERT INTO notification_comments 
                    (notification_id, user_id, comment, parent_comment_id, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("iiis", $notification_id, $user_id, $comment, $parent_comment_id);
        }
        $stmt->execute();
        $stmt->close();
    }
}

// If AJAX request, return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo json_encode(['status' => 'success']);
    exit();
}

// Otherwise, redirect back to notifications page
header("Location: viewall_notifications.php");
exit();
