<?php
session_start();
include 'config/dbcon.php';

// --- DELETE Notification ---
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $con->prepare("DELETE FROM notifications WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Notification deleted successfully.";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['status'] = "Error deleting notification: " . $stmt->error;
        $_SESSION['msg_type'] = "error";
    }

    $stmt->close();
    header("Location: notices.php"); // redirect back to list
    exit();
}

// --- EDIT Notification ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $type = trim($_POST['type'] ?? '');

    // Validate required fields
    if ($id <= 0 || $message === '' || $type === '') {
        $_SESSION['status'] = "All fields are required.";
        $_SESSION['msg_type'] = "error";
        header("Location: notices.php");
        exit();
    }

    // Handle optional file upload
    $banner_path = null;
    if (isset($_FILES['banner']) && !empty($_FILES['banner']['name'])) {
        $target_dir = "uploads/banners/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = basename($_FILES['banner']['name']);
        $target_file = $target_dir . $file_name;

        $allowed_types = ['jpg','jpeg','png','gif','mp4','webm'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            $_SESSION['status'] = "Invalid file type.";
            $_SESSION['msg_type'] = "error";
            header("Location: notices.php");
            exit();
        }

        if (!move_uploaded_file($_FILES['banner']['tmp_name'], $target_file)) {
            $_SESSION['status'] = "Failed to upload file.";
            $_SESSION['msg_type'] = "error";
            header("Location: notices.php");
            exit();
        }

        $banner_path = $con->real_escape_string($target_file);
    }

    // Prepare SQL
    if ($banner_path !== null) {
        $stmt = $con->prepare("UPDATE notifications SET title=?, message=?, type=?, file_path=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $message, $type, $banner_path, $id);
    } else {
        $stmt = $con->prepare("UPDATE notifications SET title=?,message=?, type=? WHERE id=?");
        $stmt->bind_param("sssi", $title,$message, $type, $id);
    }

    // Execute
    if ($stmt->execute()) {
        $_SESSION['status'] = "Notification updated successfully.";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['status'] = "Error updating notification: " . $stmt->error;
        $_SESSION['msg_type'] = "error";
    }

    $stmt->close();
    $con->close();
    header("Location: notices.php"); // redirect back to list
    exit();
}

// --- If no delete or edit request ---
header("Location: notices.php");
exit();
