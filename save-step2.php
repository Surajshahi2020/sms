<?php
// save-step2.php
session_start();
// debugging helpers
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/authentication.php';
include 'config/dbcon.php';

if (isset($_POST['save-step2.php'])) {
    // debugging: confirm entry and log POST data
    error_log('save-step2.php invoked, POST data: ' . json_encode($_POST));
    echo '<!-- debug: save-step2 POST received -->';
    // if debug flag is provided in URL, dump POST and stop
    if (isset($_GET['dbg'])) {
        echo '<h3>POST data:</h3><pre>' . htmlspecialchars(print_r($_POST, true)) . '</pre>';
        exit();
    }
    $user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;
    if ($user_id == 0) {
        $_SESSION['status'] = "कृपया लग इन गर्नुहोस्।";
        $_SESSION['msg_type'] = "error";
        header("Location: login.php");
        exit();
    }

    $server_id = isset($_POST['server_id']) ? intval($_POST['server_id']) : 0;

    // collect and sanitize all OS-related form values
    // operating system checkbox selections
    $os_windows = mysqli_real_escape_string($con, trim($_POST['os_windows'] ?? ''));
    $os_linux = mysqli_real_escape_string($con, trim($_POST['os_linux'] ?? ''));
    $os_other = mysqli_real_escape_string($con, trim($_POST['os_other'] ?? ''));
    $os_windows_chk = isset($_POST['os_windows_chk']) ? 1 : 0;
    $os_linux_chk = isset($_POST['os_linux_chk']) ? 1 : 0;
    $os_other_chk = isset($_POST['os_other_chk']) ? 1 : 0;
    if (!$os_windows_chk) $os_windows = '';
    if (!$os_linux_chk) $os_linux = '';
    if (!$os_other_chk) $os_other = '';
    // overall database update status
    $db_updated_overall = mysqli_real_escape_string($con, trim($_POST['db_updated_overall'] ?? ''));
    $db_updated_details = mysqli_real_escape_string($con, trim($_POST['db_updated_details'] ?? ''));
    $os_patches_status = mysqli_real_escape_string($con, trim($_POST['os_patches_status'] ?? ''));
    $os_patches_details = mysqli_real_escape_string($con, trim($_POST['os_patches_details'] ?? ''));
    $databases = mysqli_real_escape_string($con, trim($_POST['databases'] ?? ''));
    $db_admin = mysqli_real_escape_string($con, trim($_POST['db_admin'] ?? ''));
    $db_oracle_status = mysqli_real_escape_string($con, trim($_POST['db_oracle_status'] ?? ''));
    $db_oracle_version = mysqli_real_escape_string($con, trim($_POST['db_oracle_version'] ?? ''));
    $db_oracle_update = mysqli_real_escape_string($con, trim($_POST['db_oracle_update'] ?? ''));
    $db_mysql_status = mysqli_real_escape_string($con, trim($_POST['db_mysql_status'] ?? ''));
    $db_mysql_version = mysqli_real_escape_string($con, trim($_POST['db_mysql_version'] ?? ''));
    $db_mysql_update = mysqli_real_escape_string($con, trim($_POST['db_mysql_update'] ?? ''));
    $db_others_status = mysqli_real_escape_string($con, trim($_POST['db_others_status'] ?? ''));
    $db_others_details = mysqli_real_escape_string($con, trim($_POST['db_others_details'] ?? ''));
    $db_others_update = mysqli_real_escape_string($con, trim($_POST['db_others_update'] ?? ''));
    $services_frameworks = mysqli_real_escape_string($con, trim($_POST['services_frameworks'] ?? ''));
    $admin_computer = mysqli_real_escape_string($con, trim($_POST['admin_computer'] ?? ''));
    $admin_count = intval($_POST['admin_count'] ?? 0);
    $admin_count_reason = mysqli_real_escape_string($con, trim($_POST['admin_count_reason'] ?? ''));
    $normal_users_count = intval($_POST['normal_users_count'] ?? 0);
    $normal_users_details = mysqli_real_escape_string($con, trim($_POST['normal_users_details'] ?? ''));
    $password_policy_status = mysqli_real_escape_string($con, trim($_POST['password_policy_status'] ?? ''));
    $password_length_details = mysqli_real_escape_string($con, trim($_POST['password_length_details'] ?? ''));
    $password_combo_details = mysqli_real_escape_string($con, trim($_POST['password_combo_details'] ?? ''));
    $password_policy_reason = mysqli_real_escape_string($con, trim($_POST['password_policy_reason'] ?? ''));
    $password_logbook_status = mysqli_real_escape_string($con, trim($_POST['password_logbook_status'] ?? ''));
    $password_logbook_details = mysqli_real_escape_string($con, trim($_POST['password_logbook_details'] ?? ''));
    $server_type = mysqli_real_escape_string($con, trim($_POST['server_type'] ?? ''));
    $server_type_other = mysqli_real_escape_string($con, trim($_POST['server_type_other'] ?? ''));
    $certificate_used = mysqli_real_escape_string($con, trim($_POST['certificate_used'] ?? ''));
    $certificate_details = mysqli_real_escape_string($con, trim($_POST['certificate_details'] ?? ''));
    $certificate_expiry = mysqli_real_escape_string($con, trim($_POST['certificate_expiry'] ?? ''));
    $antivirus_installed = mysqli_real_escape_string($con, trim($_POST['antivirus_installed'] ?? ''));
    $antivirus_details = mysqli_real_escape_string($con, trim($_POST['antivirus_details'] ?? ''));
    $antivirus_updated = mysqli_real_escape_string($con, trim($_POST['antivirus_updated'] ?? ''));
    $antivirus_update_details = mysqli_real_escape_string($con, trim($_POST['antivirus_update_details'] ?? ''));
    $selinux_enabled = mysqli_real_escape_string($con, trim($_POST['selinux_enabled'] ?? ''));
    $selinux_details = mysqli_real_escape_string($con, trim($_POST['selinux_details'] ?? ''));
    $remote_admin_enabled = mysqli_real_escape_string($con, trim($_POST['remote_admin_enabled'] ?? ''));
    $remote_admin_details = mysqli_real_escape_string($con, trim($_POST['remote_admin_details'] ?? ''));
    $remote_policy_followed = mysqli_real_escape_string($con, trim($_POST['remote_policy_followed'] ?? ''));
    $remote_policy_details = mysqli_real_escape_string($con, trim($_POST['remote_policy_details'] ?? ''));

    // no strict validation; you can add rules if needed
    mysqli_begin_transaction($con);
    try {
        // verify or set existing server
        if ($server_id > 0) {
            // check ownership
            $check_query = "SELECT id FROM basic_info WHERE id = ? AND user_id = ?";
            $check_stmt = mysqli_prepare($con, $check_query);
            mysqli_stmt_bind_param($check_stmt, "ii", $server_id, $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            if (mysqli_stmt_num_rows($check_stmt) == 0) {
                throw new Exception("तपाईंलाई यो सर्भर अपडेट गर्ने अनुमति छैन।");
            }
            mysqli_stmt_close($check_stmt);

            $query = "UPDATE basic_info SET 
                        os_windows = ?,
                        os_linux = ?,
                        os_other = ?,
                        os_patches_status = ?,
                        os_patches_details = ?,
                        db_admin = ?,
                        db_oracle_status = ?,
                        db_oracle_version = ?,
                        db_oracle_update = ?,
                        db_mysql_status = ?,
                        db_mysql_version = ?,
                        db_mysql_update = ?,
                        db_others_status = ?,
                        db_others_details = ?,
                        db_others_update = ?,
                        db_updated_overall = ?,
                        db_updated_details = ?,
                        services_frameworks = ?,
                        admin_computer = ?,
                        admin_count = ?,
                        admin_count_reason = ?,
                        normal_users_count = ?,
                        normal_users_details = ?,
                        password_policy_status = ?,
                        password_policy_reason = ?,
                        password_length_details = ?,
                        password_combo_details = ?,
                        password_logbook_status = ?,
                        password_logbook_details = ?,
                        server_type = ?,
                        server_type_other = ?,
                        certificate_used = ?,
                        certificate_details = ?,
                        certificate_expiry = ?,
                        antivirus_installed = ?,
                        antivirus_details = ?,
                        antivirus_updated = ?,
                        antivirus_update_details = ?,
                        selinux_enabled = ?,
                        selinux_details = ?,
                        remote_admin_enabled = ?,
                        remote_admin_details = ?,
                        remote_policy_followed = ?,
                        remote_policy_details = ?,
                        updated_at = NOW()
                      WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($con, $query);
            // bind values in the exact order of the query columns above (updated fields)
            $updateParams = [
                $os_windows,
                $os_linux,
                $os_other,
                $os_patches_status,
                $os_patches_details,
                $db_admin,
                $db_oracle_status,
                $db_oracle_version,
                $db_oracle_update,
                $db_mysql_status,
                $db_mysql_version,
                $db_mysql_update,
                $db_others_status,
                $db_others_details,
                $db_others_update,
                $db_updated_overall,
                $db_updated_details,
                $services_frameworks,
                $admin_computer,
                $admin_count,
                $admin_count_reason,
                $normal_users_count,
                $normal_users_details,
                $password_policy_status,
                $password_policy_reason,
                $password_length_details,
                $password_combo_details,
                $password_logbook_status,
                $password_logbook_details,
                $server_type,
                $server_type_other,
                $certificate_used,
                $certificate_details,
                $certificate_expiry,
                $antivirus_installed,
                $antivirus_details,
                $antivirus_updated,
                $antivirus_update_details,
                $selinux_enabled,
                $selinux_details,
                $remote_admin_enabled,
                $remote_admin_details,
                $remote_policy_followed,
                $remote_policy_details,
                $server_id,
                $user_id
            ];
            $types = str_repeat('s', count($updateParams));
            mysqli_stmt_bind_param($stmt, $types, ...$updateParams);
            $status_message = "ओएस जानकारी सफलतापूर्वक अपडेट गरियो!";
        } else {
            // new record (same query as update but with user_id first)
            // columns and values are ordered to match update query above; new fields added as needed
            $query = "INSERT INTO basic_info (
                        user_id,
                        os_windows,
                        os_linux,
                        os_other,
                        os_patches_status,
                        os_patches_details,
                        db_admin,
                        db_oracle_status,
                        db_oracle_version,
                        db_oracle_update,
                        db_mysql_status,
                        db_mysql_version,
                        db_mysql_update,
                        db_others_status,
                        db_others_details,
                        db_others_update,
                        db_updated_overall,
                        db_updated_details,
                        services_frameworks,
                        admin_computer,
                        admin_count,
                        admin_count_reason,
                        normal_users_count,
                        normal_users_details,
                        password_policy_status,
                        password_policy_reason,
                        password_length_details,
                        password_combo_details,
                        password_logbook_status,
                        password_logbook_details,
                        server_type,
                        server_type_other,
                        certificate_used,
                        certificate_details,
                        certificate_expiry,
                        antivirus_installed,
                        antivirus_details,
                        antivirus_updated,
                        antivirus_update_details,
                        selinux_enabled,
                        selinux_details,
                        remote_admin_enabled,
                        remote_admin_details,
                        remote_policy_followed,
                        remote_policy_details,
                        status,
                        created_at,
                        updated_at
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW(), NOW())";
            $stmt = mysqli_prepare($con, $query);
            // bind parameters matching the above column order (updated fields)
            mysqli_stmt_bind_param($stmt, "issssssssssssississssssssssssssssssss", 
                $user_id,
                $os_windows,
                $os_linux,
                $os_other,
                $os_patches_status,
                $os_patches_details,
                $db_admin,
                $db_oracle_status,
                $db_oracle_version,
                $db_oracle_update,
                $db_mysql_status,
                $db_mysql_version,
                $db_mysql_update,
                $db_others_status,
                $db_others_details,
                $db_others_update,
                $db_updated_overall,
                $db_updated_details,
                $services_frameworks,
                $admin_computer,
                $admin_count,
                $admin_count_reason,
                $normal_users_count,
                $normal_users_details,
                $password_policy_status,
                $password_policy_reason,
                $password_length_details,
                $password_combo_details,
                $password_logbook_status,
                $password_logbook_details,
                $server_type,
                $server_type_other,
                $certificate_used,
                $certificate_details,
                $certificate_expiry,
                $antivirus_installed,
                $antivirus_details,
                $antivirus_updated,
                $antivirus_update_details,
                $selinux_enabled,
                $selinux_details,
                $remote_admin_enabled,
                $remote_admin_details,
                $remote_policy_followed,
                $remote_policy_details
            );
            $status_message = "ओएस जानकारी सफलतापूर्वक सेभ गरियो!";
        }

        if (mysqli_stmt_execute($stmt)) {
            if ($server_id == 0) {
                $server_id = mysqli_insert_id($con);
            }

            // --- synchronize with server_os_info table ---
            // check if a row already exists for this basic_info
            $os_check_query = "SELECT id FROM server_os_info WHERE basic_info_id = ?";
            $os_check_stmt = mysqli_prepare($con, $os_check_query);
            mysqli_stmt_bind_param($os_check_stmt, "i", $server_id);
            mysqli_stmt_execute($os_check_stmt);
            mysqli_stmt_store_result($os_check_stmt);
            $params = [
                $user_id,
                $os_windows,
                $os_linux,
                $os_other,
                $os_patches_status,
                $os_patches_details,
                $databases,
                $db_admin,
                $db_updated_overall,
                $db_updated_details,
                $services_frameworks,
                $admin_computer,
                $admin_count,
                $admin_count_reason,
                $normal_users_count,
                $normal_users_details,
                $password_policy_status,
                $password_length_details,
                $password_combo_details,
                $password_policy_reason,
                $password_logbook_status,
                $password_logbook_details,
                $server_type,
                $server_type_other,
                $certificate_used,
                $certificate_details,
                $certificate_expiry,
                $antivirus_installed,
                $antivirus_details,
                $antivirus_updated,
                $antivirus_update_details,
                $selinux_enabled,
                $selinux_details,
                $remote_admin_enabled,
                $remote_admin_details,
                $remote_policy_followed,
                $remote_policy_details
            ];
            // all parameters treated as strings except ids
            $types = str_repeat('s', count($params));

            if (mysqli_stmt_num_rows($os_check_stmt) > 0) {
                // update existing OS info record
                $update_os = "
                  UPDATE server_os_info SET
                      user_id = ?,
                      os_windows = ?,
                      os_linux = ?,
                      os_other = ?,
                      os_patches_status = ?,
                      os_patches_details = ?,
                      installed_databases = ?,
                      db_admin = ?,
                      db_updated_overall = ?,
                      db_updated_details = ?,
                      services_frameworks = ?,
                      admin_computer = ?,
                      admin_count = ?,
                      admin_count_reason = ?,
                      normal_users_count = ?,
                      normal_users_details = ?,
                      password_policy_status = ?,
                      password_length_details = ?,
                      password_combo_details = ?,
                      password_policy_reason = ?,
                      password_logbook_status = ?,
                      password_logbook_details = ?,
                      server_type = ?,
                      server_type_other = ?,
                      certificate_used = ?,
                      certificate_details = ?,
                      certificate_expiry = ?,
                      antivirus_installed = ?,
                      antivirus_details = ?,
                      antivirus_updated = ?,
                      antivirus_update_details = ?,
                      selinux_enabled = ?,
                      selinux_details = ?,
                      remote_admin_enabled = ?,
                      remote_admin_details = ?,
                      remote_policy_followed = ?,
                      remote_policy_details = ?,
                      updated_at = NOW()
                  WHERE basic_info_id = ?";
                $os_upd = mysqli_prepare($con, $update_os);
                // append server_id to params list
                $upd_params = array_merge($params, [$server_id]);
                $upd_types = $types . 's';
                mysqli_stmt_bind_param($os_upd, $upd_types, ...$upd_params);
                if (!mysqli_stmt_execute($os_upd)) {
                    throw new Exception('OS info update failed: ' . mysqli_stmt_error($os_upd));
                }
                mysqli_stmt_close($os_upd);
            } else {
                // insert new OS info row
                $insert_os = "
                  INSERT INTO server_os_info (
                      user_id, basic_info_id,
                      os_windows, os_linux, os_other,
                      os_patches_status, os_patches_details,
                      installed_databases, db_admin,
                      db_updated_overall, db_updated_details,
                      services_frameworks, admin_computer,
                      admin_count, admin_count_reason,
                      normal_users_count, normal_users_details,
                      password_policy_status, password_length_details,
                      password_combo_details, password_policy_reason,
                      password_logbook_status, password_logbook_details,
                      server_type, server_type_other,
                      certificate_used, certificate_details,
                      certificate_expiry, antivirus_installed,
                      antivirus_details, antivirus_updated,
                      antivirus_update_details, selinux_enabled,
                      selinux_details, remote_admin_enabled,
                      remote_admin_details, remote_policy_followed,
                      remote_policy_details,
                      created_at, updated_at
                  ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
                $os_ins = mysqli_prepare($con, $insert_os);
                $ins_params = array_merge([$user_id, $server_id], $params);
                $ins_types = 'ss' . $types;
                mysqli_stmt_bind_param($os_ins, $ins_types, ...$ins_params);
                if (!mysqli_stmt_execute($os_ins)) {
                    throw new Exception('OS info insert failed: ' . mysqli_stmt_error($os_ins));
                }
                mysqli_stmt_close($os_ins);
            }
            mysqli_stmt_close($os_check_stmt);
            // --- end sync ---

            mysqli_commit($con);
            $_SESSION['status'] = $status_message;
            $_SESSION['msg_type'] = "success";
            mysqli_stmt_close($stmt);
            header("Location: step2.php?server_id=" . $server_id);
            exit();
        } else {
            mysqli_rollback($con);
            throw new Exception(mysqli_error($con));
        }
    } catch (Exception $e) {
        mysqli_rollback($con);
        $err = $e->getMessage();
        // log to PHP error log as well
        error_log("save-step2.php error: " . $err);
        $_SESSION['status'] = "डाटा सेभ गर्दा त्रुटि भयो: " . $err;
        $_SESSION['msg_type'] = "error";
        header("Location: step2.php" . ($server_id > 0 ? "?server_id=" . $server_id : ""));
        exit();
    }
} else {
    header("Location: step2.php");
    exit();
}
