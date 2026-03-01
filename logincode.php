<?php
session_start();
date_default_timezone_set('Asia/Kathmandu');
include 'config/dbcon.php';

function getUserIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

if (isset($_POST['login_btn'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['message'] = "ईमेल र पासवर्ड अनिवार्य छन्।";
        $_SESSION['msg_type'] = "warning";
        header("Location: login.php");
        exit;
    }

    $stmt = $con->prepare("SELECT id, full_name_ne, email, password, role_as, is_active 
                           FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['id'];
        $user_name = $row['full_name_ne'];
        $ip = getUserIpAddr();
        $time = date('Y-m-d H:i:s');
        $table_name = 'users';
        $action = 'LOGIN';

        if (password_verify($password, $row['password'])) {

            if ($row['is_active'] != 1) {
                $_SESSION['status'] = "तपाईंको खाता सक्रिय छैन। कृपया प्रशासकलाई सम्पर्क गर्नुहोस्।";
                $_SESSION['msg_type'] = "error";
                header("Location: login.php");
                exit;
            }

            // Set session
            $_SESSION['auth'] = $row['role_as'];
            $_SESSION['auth_user'] = [
                'user_id'    => $user_id,
                'user_name'  => $user_name,
                'user_email' => $row['email'],
                'role_as'    => $row['role_as']
            ];


            // Audit log for successful login
            $new_data = json_encode([
                'user_id' => $user_id,
                'email'   => $row['email'],
                'status'  => 'SUCCESS',
                'ip'      => $ip,
                'time'    => $time
            ], JSON_UNESCAPED_SLASHES);

            $audit_stmt = $con->prepare("INSERT INTO audit_log 
                (table_name, record_id, action_type, old_data, new_data, performed_by, user_name) 
                VALUES (?, ?, ?, '[]', ?, ?, ?)");
            $audit_stmt->bind_param("sissis", $table_name, $user_id, $action, $new_data, $user_id, $user_name);
            $audit_stmt->execute();

            $_SESSION['status'] = "सफलतापूर्वक लगइन भयो — स्वागत छ, " . htmlspecialchars($user_name) . "!";
            $_SESSION['msg_type'] = "success";
            $_SESSION['show_notification'] = true;
            header("Location: index.php");
            exit;

        } else {
            // Password incorrect — insert audit log
            $ip = getUserIpAddr();
            $time = date('Y-m-d H:i:s');

            $new_data = json_encode([
                'email'  => $email,
                'status' => 'FAILED',
                'ip'     => $ip,
                'time'   => $time
            ], JSON_UNESCAPED_SLASHES);

            $audit_stmt = $con->prepare("INSERT INTO audit_log 
                (table_name, record_id, action_type, old_data, new_data, performed_by, user_name) 
                VALUES (?, ?, ?, '[]', ?, ?, ?)");
            // performed_by is the user who exists, so use $row['id']
            $audit_stmt->bind_param("sissis", $table_name, $row['id'], $action, $new_data, $row['id'], $user_name);
            $audit_stmt->execute();

            $_SESSION['status'] = "ईमेल वा पासवर्ड गलत छ।";
            $_SESSION['msg_type'] = "error";
            header("Location: login.php");
            exit;
        }

    } else {
        // User does not exist — do NOT insert into audit_log
        $_SESSION['status'] = "ईमेल वा पासवर्ड गलत छ।";
        $_SESSION['msg_type'] = "error";
        header("Location: login.php");
        exit;
    }

} else {
    $_SESSION['status'] = "पहुँच अस्वीकृत।";
    $_SESSION['msg_type'] = "error";
    header("Location: login.php");
    exit;
}
?>
