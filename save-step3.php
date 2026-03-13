<?php
// save-step3.php - Save Data Security (Step 3)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/authentication.php';
include 'config/dbcon.php';

if (isset($_POST['save_step3']) || isset($_POST['save_and_next'])) {
    
    $user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;
    if ($user_id == 0) {
        $_SESSION['status'] = "कृपया लग इन गर्नुहोस्।";
        $_SESSION['msg_type'] = "error";
        header("Location: login.php");
        exit();
    }

    $server_id = isset($_POST['server_id']) ? intval($_POST['server_id']) : 0;
    
    // Verify server belongs to user
    if ($server_id > 0) {
        $check_query = "SELECT id FROM basic_info WHERE id = ? AND user_id = ?";
        $check_stmt = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($check_stmt, "ii", $server_id, $user_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if (mysqli_stmt_num_rows($check_stmt) == 0) {
            $_SESSION['status'] = "तपाईंलाई यो सर्भर अपडेट गर्ने अनुमति छैन।";
            $_SESSION['msg_type'] = "error";
            header("Location: step3.php");
            exit();
        }
        mysqli_stmt_close($check_stmt);
    } else {
        $_SESSION['status'] = "कृपया पहिले स्टेप ১ मा गएर सर्भर चयन गर्नुहोस्।";
        $_SESSION['msg_type'] = "error";
        header("Location: step1.php");
        exit();
    }

    // A. Authorization of Data
    $auth_data = isset($_POST['auth_data']) ? 'yes' : 'no';
    $auth_data_details = isset($_POST['auth_data_details']) ? trim($_POST['auth_data_details']) : '';

    // B. Authentication of user
    $auth_method = isset($_POST['auth_method']) ? trim($_POST['auth_method']) : '';
    $auth_method_other = isset($_POST['auth_method_other']) ? trim($_POST['auth_method_other']) : '';
    $auth_details = isset($_POST['auth_details']) ? trim($_POST['auth_details']) : '';

    // C. Data Access control for user
    $access_rbac = isset($_POST['access_rbac']) ? 'yes' : 'no';
    $access_mac = isset($_POST['access_mac']) ? 'yes' : 'no';
    $access_dac = isset($_POST['access_dac']) ? 'yes' : 'no';
    $access_rule = isset($_POST['access_rule']) ? 'yes' : 'no';
    $access_other_check = isset($_POST['access_other_check']) ? 'yes' : 'no';
    $access_other = isset($_POST['access_other']) ? trim($_POST['access_other']) : '';
    $access_details = isset($_POST['access_details']) ? trim($_POST['access_details']) : '';

    // D. Privilege based user
    $privilege_based = isset($_POST['privilege_based']) ? trim($_POST['privilege_based']) : '';
    $privilege_sop = isset($_POST['privilege_sop']) ? trim($_POST['privilege_sop']) : '';

    // E. Encryption
    $encryption_method = isset($_POST['encryption_method']) ? trim($_POST['encryption_method']) : '';
    $encryption_method_other = isset($_POST['encryption_method_other']) ? trim($_POST['encryption_method_other']) : '';
    $encryption_details = isset($_POST['encryption_details']) ? trim($_POST['encryption_details']) : '';

    // F. Off hour administration
    $offhour_duty = isset($_POST['offhour_duty']) ? trim($_POST['offhour_duty']) : '';
    $offhour_duty_other = isset($_POST['offhour_duty_other']) ? trim($_POST['offhour_duty_other']) : '';
    $duty_sop = isset($_POST['duty_sop']) ? trim($_POST['duty_sop']) : '';
    $offhour_admin = isset($_POST['offhour_admin']) ? trim($_POST['offhour_admin']) : '';
    $offhour_admin_other = isset($_POST['offhour_admin_other']) ? trim($_POST['offhour_admin_other']) : '';
    $offhour_details = isset($_POST['offhour_details']) ? trim($_POST['offhour_details']) : '';

    // Remarks
    $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : '';

    // Check if record exists
    $check_query = "SELECT id FROM data_security WHERE server_id = ?";
    $check_stmt = mysqli_prepare($con, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $server_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    $record_exists = mysqli_stmt_num_rows($check_stmt) > 0;
    mysqli_stmt_close($check_stmt);

    if ($record_exists) {
        // Update existing record - 24 string params + 1 int param = 25 total
        $sql = "UPDATE data_security SET 
            auth_data = ?, auth_data_details = ?,
            auth_method = ?, auth_method_other = ?, auth_details = ?,
            access_rbac = ?, access_mac = ?, access_dac = ?, access_rule = ?,
            access_other_check = ?, access_other = ?, access_details = ?,
            privilege_based = ?, privilege_sop = ?,
            encryption_method = ?, encryption_method_other = ?, encryption_details = ?,
            offhour_duty = ?, offhour_duty_other = ?, duty_sop = ?,
            offhour_admin = ?, offhour_admin_other = ?, offhour_details = ?,
            remarks = ?
            WHERE server_id = ?";
        
        $stmt = mysqli_prepare($con, $sql);
        $params = [
            $auth_data, $auth_data_details,
            $auth_method, $auth_method_other, $auth_details,
            $access_rbac, $access_mac, $access_dac, $access_rule,
            $access_other_check, $access_other, $access_details,
            $privilege_based, $privilege_sop,
            $encryption_method, $encryption_method_other, $encryption_details,
            $offhour_duty, $offhour_duty_other, $duty_sop,
            $offhour_admin, $offhour_admin_other, $offhour_details,
            $remarks, $server_id
        ];
        // 24 strings + 1 integer
        $types = str_repeat('s', 24) . 'i';
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        // Insert new record - 2 int + 24 string = 26 total
        $sql = "INSERT INTO data_security (
            server_id, user_id,
            auth_data, auth_data_details,
            auth_method, auth_method_other, auth_details,
            access_rbac, access_mac, access_dac, access_rule,
            access_other_check, access_other, access_details,
            privilege_based, privilege_sop,
            encryption_method, encryption_method_other, encryption_details,
            offhour_duty, offhour_duty_other, duty_sop,
            offhour_admin, offhour_admin_other, offhour_details,
            remarks
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        $params = [
            $server_id, $user_id,
            $auth_data, $auth_data_details,
            $auth_method, $auth_method_other, $auth_details,
            $access_rbac, $access_mac, $access_dac, $access_rule,
            $access_other_check, $access_other, $access_details,
            $privilege_based, $privilege_sop,
            $encryption_method, $encryption_method_other, $encryption_details,
            $offhour_duty, $offhour_duty_other, $duty_sop,
            $offhour_admin, $offhour_admin_other, $offhour_details,
            $remarks
        ];
        // 2 integers + 24 strings
        $types = 'ii' . str_repeat('s', 24);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        $_SESSION['status'] = "डेटा सुरक्षा सफलतापूर्वक सेव गरियो।";
        $_SESSION['msg_type'] = "success";
        
        if (isset($_POST['save_and_next'])) {
            header("Location: step4.php");
        } else {
            header("Location: step3.php?server_id=" . $server_id);
        }
        exit();
    } else {
        $err = mysqli_error($con);
        error_log("save-step3.php error: " . $err);
        $_SESSION['status'] = "डाटा सेभ गर्दा त्रुटि भयो: " . $err;
        $_SESSION['msg_type'] = "error";
        header("Location: step3.php?server_id=" . $server_id);
        exit();
    }
} else {
    header("Location: step3.php");
    exit();
}
?>
