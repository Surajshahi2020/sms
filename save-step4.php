<?php
// save-step4.php - Save Network Security (Step 4)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/authentication.php';
include 'config/dbcon.php';

if (isset($_POST['save_step4']) || isset($_POST['save_and_next'])) {
    
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
            header("Location: step4.php");
            exit();
        }
        mysqli_stmt_close($check_stmt);
    } else {
        $_SESSION['status'] = "कृपया पहिले सर्भर चयन गर्नुहोस्।";
        $_SESSION['msg_type'] = "error";
        header("Location: step4.php");
        exit();
    }

    // A. Firewall
    $firewall_status = isset($_POST['firewall_status']) ? trim($_POST['firewall_status']) : '';
    $firewall_vendor = isset($_POST['firewall_vendor']) ? trim($_POST['firewall_vendor']) : '';
    $firewall_model = isset($_POST['firewall_model']) ? trim($_POST['firewall_model']) : '';
    $firewall_detail = isset($_POST['firewall_detail']) ? trim($_POST['firewall_detail']) : '';
    $firewall_no_reason = isset($_POST['firewall_no_reason']) ? trim($_POST['firewall_no_reason']) : '';

    // B. Firewall architecture
    $multivendor_status = isset($_POST['multivendor_status']) ? trim($_POST['multivendor_status']) : '';
    $multivendor_detail = isset($_POST['multivendor_detail']) ? trim($_POST['multivendor_detail']) : '';
    $cascaded_status = isset($_POST['cascaded_status']) ? trim($_POST['cascaded_status']) : '';
    $cascaded_type = isset($_POST['cascaded_type']) ? trim($_POST['cascaded_type']) : '';
    $cascaded_detail = isset($_POST['cascaded_detail']) ? trim($_POST['cascaded_detail']) : '';

    // C. Types of Firewall
    $firewall_types = [];
    if (isset($_POST['firewall_type_packet'])) $firewall_types[] = 'Packet Filtering';
    if (isset($_POST['firewall_type_stateful'])) $firewall_types[] = 'Stateful Inspection';
    if (isset($_POST['firewall_type_application'])) $firewall_types[] = 'Application Layer';
    if (isset($_POST['firewall_type_ngfw'])) $firewall_types[] = 'Next-Generation';
    if (isset($_POST['firewall_type_waf'])) $firewall_types[] = 'WAF';
    $firewall_types_str = implode(', ', $firewall_types);
    $firewall_types_detail = isset($_POST['firewall_types_detail']) ? trim($_POST['firewall_types_detail']) : '';

    // D. Network Segmentation
    $segmentation_status = isset($_POST['segmentation_status']) ? trim($_POST['segmentation_status']) : '';
    $segmentation_detail = isset($_POST['segmentation_detail']) ? trim($_POST['segmentation_detail']) : '';

    // E. IDS/IPS
    $ids_status = isset($_POST['ids_status']) ? trim($_POST['ids_status']) : '';
    $ids_type = isset($_POST['ids_type']) ? trim($_POST['ids_type']) : '';
    $ids_detail = isset($_POST['ids_detail']) ? trim($_POST['ids_detail']) : '';

    // F. VPN
    $vpn_status = isset($_POST['vpn_status']) ? trim($_POST['vpn_status']) : '';
    $vpn_type = isset($_POST['vpn_type']) ? trim($_POST['vpn_type']) : '';
    $vpn_detail = isset($_POST['vpn_detail']) ? trim($_POST['vpn_detail']) : '';

    // G. Network Monitoring
    $monitoring_status = isset($_POST['monitoring_status']) ? trim($_POST['monitoring_status']) : '';
    $monitoring_tools = isset($_POST['monitoring_tools']) ? trim($_POST['monitoring_tools']) : '';
    $monitoring_detail = isset($_POST['monitoring_detail']) ? trim($_POST['monitoring_detail']) : '';

    // H. Ports and Services
    $ports_review = isset($_POST['ports_review']) ? trim($_POST['ports_review']) : '';
    $unused_services = isset($_POST['unused_services']) ? trim($_POST['unused_services']) : '';
    $ports_detail = isset($_POST['ports_detail']) ? trim($_POST['ports_detail']) : '';

    // Review Information
    $next_review_date = isset($_POST['next_review_date']) ? trim($_POST['next_review_date']) : '';
    $reviewer_name = isset($_POST['reviewer_name']) ? trim($_POST['reviewer_name']) : '';

    // Check if record exists
    $check_query = "SELECT id FROM network_security WHERE server_id = ?";
    $check_stmt = mysqli_prepare($con, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $server_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    $record_exists = mysqli_stmt_num_rows($check_stmt) > 0;
    mysqli_stmt_close($check_stmt);

    if ($record_exists) {
        // Update existing record
        $sql = "UPDATE network_security SET 
            firewall_status = ?, firewall_vendor = ?, firewall_model = ?, firewall_detail = ?, firewall_no_reason = ?,
            multivendor_status = ?, multivendor_detail = ?, cascaded_status = ?, cascaded_type = ?, cascaded_detail = ?,
            firewall_types = ?, firewall_types_detail = ?,
            segmentation_status = ?, segmentation_detail = ?,
            ids_status = ?, ids_type = ?, ids_detail = ?,
            vpn_status = ?, vpn_type = ?, vpn_detail = ?,
            monitoring_status = ?, monitoring_tools = ?, monitoring_detail = ?,
            ports_review = ?, unused_services = ?, ports_detail = ?,
            next_review_date = ?, reviewer_name = ?
            WHERE server_id = ?";
        
        $stmt = mysqli_prepare($con, $sql);
        $params = [
            $firewall_status, $firewall_vendor, $firewall_model, $firewall_detail, $firewall_no_reason,
            $multivendor_status, $multivendor_detail, $cascaded_status, $cascaded_type, $cascaded_detail,
            $firewall_types_str, $firewall_types_detail,
            $segmentation_status, $segmentation_detail,
            $ids_status, $ids_type, $ids_detail,
            $vpn_status, $vpn_type, $vpn_detail,
            $monitoring_status, $monitoring_tools, $monitoring_detail,
            $ports_review, $unused_services, $ports_detail,
            $next_review_date, $reviewer_name, $server_id
        ];
        $types = str_repeat('s', 27) . 'i';
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    } else {
        // Insert new record
        $sql = "INSERT INTO network_security (
            server_id, user_id,
            firewall_status, firewall_vendor, firewall_model, firewall_detail,
            multivendor_status, multivendor_detail, cascaded_status, cascaded_type, cascaded_detail,
            firewall_types, firewall_types_detail,
            segmentation_status, segmentation_detail,
            ids_status, ids_type, ids_detail,
            vpn_status, vpn_type, vpn_detail,
            monitoring_status, monitoring_tools, monitoring_detail,
            ports_review, unused_services, ports_detail,
            next_review_date, reviewer_name
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        $params = [
            $server_id, $user_id,
            $firewall_status, $firewall_vendor, $firewall_model, $firewall_detail,
            $multivendor_status, $multivendor_detail, $cascaded_status, $cascaded_type, $cascaded_detail,
            $firewall_types_str, $firewall_types_detail,
            $segmentation_status, $segmentation_detail,
            $ids_status, $ids_type, $ids_detail,
            $vpn_status, $vpn_type, $vpn_detail,
            $monitoring_status, $monitoring_tools, $monitoring_detail,
            $ports_review, $unused_services, $ports_detail,
            $next_review_date, $reviewer_name
        ];
        $types = 'ii' . str_repeat('s', 26);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        
        $_SESSION['status'] = "नेटवर्क सुरक्षा सफलतापूर्वक सेव गरियो।";
        $_SESSION['msg_type'] = "success";
        
        if (isset($_POST['save_and_next'])) {
            header("Location: step5.php");
        } else {
            header("Location: step4.php?server_id=" . $server_id);
        }
        exit();
    } else {
        $err = mysqli_error($con);
        error_log("save-step4.php error: " . $err);
        $_SESSION['status'] = "डाटा सेभ गर्दा त्रुटि भयो: " . $err;
        $_SESSION['msg_type'] = "error";
        header("Location: step4.php?server_id=" . $server_id);
        exit();
    }
} else {
    header("Location: step4.php");
    exit();
}
?>
