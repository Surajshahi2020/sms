<?php
// save-step1.php
session_start();
include 'includes/authentication.php';
include 'config/dbcon.php';

// Check if form was submitted
if (isset($_POST['save']) || isset($_POST['save_and_next'])) {
    
    // Get the user_id from session
    $user_id = isset($_SESSION['auth_user']['user_id']) ? intval($_SESSION['auth_user']['user_id']) : 0;
    
    // Check if user is logged in
    if ($user_id == 0) {
        $_SESSION['status'] = "कृपया लग इन गर्नुहोस्।"; // "Please login" in Nepali
        $_SESSION['msg_type'] = "error";
        header("Location: login.php");
        exit();
    }
    
    // Get and sanitize form data
    $server_id = isset($_POST['server_id']) ? intval($_POST['server_id']) : 0;
    $server_name = mysqli_real_escape_string($con, trim($_POST['server_name'] ?? ''));
    $platform = mysqli_real_escape_string($con, $_POST['platform'] ?? '');
    $purpose = mysqli_real_escape_string($con, trim($_POST['purpose'] ?? ''));
    $services = mysqli_real_escape_string($con, trim($_POST['services'] ?? ''));
    $public_ip = mysqli_real_escape_string($con, trim($_POST['public_ip'] ?? ''));
    $private_ip = mysqli_real_escape_string($con, trim($_POST['private_ip'] ?? ''));
    $mac_address = mysqli_real_escape_string($con, trim($_POST['mac_address'] ?? ''));
    $priority = mysqli_real_escape_string($con, $_POST['priority'] ?? '');
    $reference_option = mysqli_real_escape_string($con, $_POST['reference_option'] ?? '');
    $remarks = mysqli_real_escape_string($con, trim($_POST['remarks'] ?? ''));
    
    // Validate required fields
    $errors = [];
    if (empty($server_name)) {
        $errors[] = "सर्भरको नाम आवश्यक छ।"; // "Server Name is required" in Nepali
    }
    if (empty($platform)) {
        $errors[] = "सर्भर प्लेटफर्म आवश्यक छ।"; // "Server Platform is required" in Nepali
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $_SESSION['status'] = implode("<br>", $errors);
        $_SESSION['msg_type'] = "error";
        header("Location: step1.php" . ($server_id > 0 ? "?server_id=" . $server_id : ""));
        exit();
    }
    
    // Begin transaction
    mysqli_begin_transaction($con);
    
    try {
        // Check if server with same name already exists for this user
        if ($server_id == 0) {
            // For new entries, check if server name already exists for this user
            $check_query = "SELECT id FROM servers WHERE server_name = ? AND user_id = ?";
            $check_stmt = mysqli_prepare($con, $check_query);
            mysqli_stmt_bind_param($check_stmt, "si", $server_name, $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                // Server exists, get its ID for update
                mysqli_stmt_bind_result($check_stmt, $existing_id);
                mysqli_stmt_fetch($check_stmt);
                $server_id = $existing_id;
            }
            mysqli_stmt_close($check_stmt);
        }
        
        // Now handle insert or update based on whether we have a server_id
        if ($server_id > 0) {
            // First verify that this server belongs to the user (for security)
            $check_query = "SELECT id FROM servers WHERE id = ? AND user_id = ?";
            $check_stmt = mysqli_prepare($con, $check_query);
            mysqli_stmt_bind_param($check_stmt, "ii", $server_id, $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) == 0) {
                throw new Exception("तपाईंसँग यो सर्भर अपडेट गर्ने अनुमति छैन।"); // "You don't have permission" in Nepali
            }
            mysqli_stmt_close($check_stmt);
            
            // Update existing record
            $query = "UPDATE servers SET 
                        server_name = ?,
                        platform = ?,
                        purpose = ?,
                        services = ?,
                        public_ip = ?,
                        private_ip = ?,
                        mac_address = ?,
                        priority = ?,
                        reference_option = ?,
                        remarks = ?,
                        updated_at = NOW()
                      WHERE id = ? AND user_id = ?";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "ssssssssssii", 
                $server_name, 
                $platform, 
                $purpose, 
                $services, 
                $public_ip, 
                $private_ip, 
                $mac_address, 
                $priority, 
                $reference_option, 
                $remarks,
                $server_id,
                $user_id
            );
            
            $status_message = "सर्भरको आधारभूत जानकारी सफलतापूर्वक अपडेट गरियो!"; // "Server updated" in Nepali
            
        } else {
            // Insert new record
            $query = "INSERT INTO servers (
                        user_id,
                        server_name, 
                        platform, 
                        purpose, 
                        services, 
                        public_ip, 
                        private_ip, 
                        mac_address, 
                        priority, 
                        reference_option, 
                        remarks,
                        status,
                        created_at,
                        updated_at
                      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW(), NOW())";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "issssssssss", 
                $user_id,
                $server_name, 
                $platform, 
                $purpose, 
                $services, 
                $public_ip, 
                $private_ip, 
                $mac_address, 
                $priority, 
                $reference_option, 
                $remarks
            );
            
            $status_message = "सर्भरको आधारभूत जानकारी सफलतापूर्वक सेभ गरियो!"; // "Server saved" in Nepali
        }
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            
            // Get the server_id if it was a new insert
            if ($server_id == 0) {
                $server_id = mysqli_insert_id($con);
            }
            
            // Commit transaction
            mysqli_commit($con);
            
            // Set success message using status and msg_type format
            $_SESSION['status'] = $status_message;
            $_SESSION['msg_type'] = "success";
            
            // Close statement
            mysqli_stmt_close($stmt);
            
            // Redirect based on button clicked
            if (isset($_POST['save_and_next'])) {
                header("Location: step2.php?server_id=" . $server_id);
            } else {
                header("Location: step1.php?server_id=" . $server_id);
            }
            exit();
            
        } else {
            // Rollback on error
            mysqli_rollback($con);
            throw new Exception(mysqli_error($con));
        }
        
    } catch (Exception $e) {
        // Error occurred
        mysqli_rollback($con);
        $_SESSION['status'] = "डाटा सेभ गर्दा त्रुटि भयो: " . $e->getMessage(); // "Error saving data" in Nepali
        $_SESSION['msg_type'] = "error";
        header("Location: step1.php" . ($server_id > 0 ? "?server_id=" . $server_id : ""));
        exit();
    }
    
} else {
    // If someone tries to access this file directly
    header("Location: step1.php");
    exit();
}

// Close connection
mysqli_close($con);
?>


