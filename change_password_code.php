<?php
session_start();
include 'config/dbcon.php';

if (isset($_POST['changePassword'])) {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['auth_user']['user_id'] ?? 0;

    if ($new_password !== $confirm_password) {
        $_SESSION['status'] = "❌ नयाँ पासवर्ड मिलेन।";
        $_SESSION['msg_type'] = "info";
        header("Location: change_password.php");
        exit;
    }

    if (strlen($new_password) < 4) {
        $_SESSION['status'] = "❌ पासवर्ड कम्तिमा ६ अक्षरको हुनुपर्छ।";
        $_SESSION['msg_type'] = "info";
        header("Location: change_password.php");
        exit;
    }

    $stmt = $con->prepare("SELECT password FROM users WHERE id = ? AND is_void = 0");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['status'] = "❌ प्रयोगकर्ता फेला परेन।";
        $_SESSION['msg_type'] = "info";
        header("Location: change_password.php");
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($old_password, $user['password'])) {
        $_SESSION['status'] = "❌ हालको पासवर्ड गलत छ।";
        $_SESSION['msg_type'] = "info";
        header("Location: change_password.php");
        exit;
    }

    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->bind_param('si', $hashed_new_password, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['status'] = "✅ पासवर्ड सफलतापूर्वक परिवर्तन भयो!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['status'] = "❌ डाटाबेसमा त्रुटि भयो। कृपया फेरि प्रयास गर्नुहोस्।";
        $_SESSION['msg_type'] = "info";
    }

    header("Location: index.php");
    exit;
}
?>