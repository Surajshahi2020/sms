<?php
include 'includes/authentication.php';
include 'config/dbcon.php';

header('Content-Type: application/json');

$user_id = $_SESSION['auth_user']['user_id'] ?? null;
$notification_id = $_POST['notification_id'] ?? null;

if (!$user_id || !$notification_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing user or notification ID']);
    exit;
}

// Check if record already exists
$check = $con->prepare("SELECT id FROM notification_reads WHERE user_id = ? AND notification_id = ?");
$check->bind_param("ii", $user_id, $notification_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Already exists — just update
    $update = $con->prepare("UPDATE notification_reads SET is_read = 1, read_at = NOW() WHERE user_id = ? AND notification_id = ?");
    $update->bind_param("ii", $user_id, $notification_id);
    if ($update->execute()) {
        echo json_encode(['status' => 'success', 'action' => 'updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
    }
    $update->close();
} else {
    // Insert new record
    $insert = $con->prepare("INSERT INTO notification_reads (user_id, notification_id, is_read, read_at) VALUES (?, ?, 1, NOW())");
    $insert->bind_param("ii", $user_id, $notification_id);
    if ($insert->execute()) {
        echo json_encode(['status' => 'success', 'action' => 'inserted']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to insert']);
    }
    $insert->close();
}

$check->close();
$con->close();
