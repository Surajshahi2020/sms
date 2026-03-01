<?php
session_start();

include 'config/dbcon.php';
include_once 'supporter/permissions.php';

// Ensure database connection exists
if (!isset($con) || !$con) {
    $_SESSION['status'] = "Database connection not established.";
    $_SESSION['msg_type'] = "error";
    header("Location: alerts.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['auth_user'])) {
    $_SESSION['status'] = "पहुँच अस्वीकृत।"; // Access denied
    $_SESSION['msg_type'] = "error";
    header("Location: alerts.php");
    exit();
}

// Handle form submission
if (isset($_POST['addNotification'])) {

    // Get inputs and trim
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $type = trim($_POST['type'] ?? '');

    // Validate mandatory fields
    if ($message === '' || $type === '' || $title === '') {
        $_SESSION['status'] = "शीर्षक, सन्देश र प्रकार अनिवार्य छन्।"; // Title, message, and type required
        $_SESSION['msg_type'] = "error";
        header("Location: alerts.php");
        exit();
    }

    // Handle optional file upload
    $file_path = NULL;
    if (!empty($_FILES['banner']['name'])) {
        $target_dir = "uploads/banners/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = basename($_FILES['banner']['name']);
        $target_file = $target_dir . $file_name;

        // Allowed file types
        $allowed_types = [
            'jpg','jpeg','png','gif',         // Images
            'mp4','webm',                     // Videos
            'pdf',                             // PDF
            'doc','docx','ppt','pptx','xls','xlsx' // Office files
        ];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            $_SESSION['status'] = "अवैध फाइल प्रकार। स्वीकार्य फाइलहरू: jpg, png, gif, mp4, webm, pdf, doc, docx, ppt, pptx, xls, xlsx।";
            $_SESSION['msg_type'] = "error";
            header("Location: alerts.php");
            exit();
        }

        if (!move_uploaded_file($_FILES['banner']['tmp_name'], $target_file)) {
            $_SESSION['status'] = "फाइल अपलोड गर्न असफल।";
            $_SESSION['msg_type'] = "error";
            header("Location: alerts.php");
            exit();
        }

        $file_path = $con->real_escape_string($target_file);
    }

    // Insert into database
    $stmt = $con->prepare("INSERT INTO notifications (user_id, title, message, type, file_path, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $_SESSION['auth_user']['user_id'], $title, $message, $type, $file_path);

    if ($stmt->execute()) {
        $_SESSION['status'] = "Notification सफलतापूर्वक सिर्जना भयो।";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['status'] = "त्रुटि: " . $stmt->error;
        $_SESSION['msg_type'] = "error";
    }

    $stmt->close();
    header("Location: alerts.php");
    exit();
} else {
    // Form not submitted
    $_SESSION['status'] = "फर्म सबमिट गरिएको छैन।";
    $_SESSION['msg_type'] = "error";
    header("Location: alerts.php");
    exit();
}
?>
