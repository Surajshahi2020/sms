<?php
include('includes/authentication.php');
include('config/dbcon.php');
date_default_timezone_set('Asia/Kathmandu');

// Check if logout is requested via GET
if (isset($_GET['logout'])) {
    // Clear all relevant session variables
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);

    // Optionally destroy the session completely
    session_destroy();

    // Start a fresh session to set the status message
    session_start();
    $_SESSION['status'] = "Logged out successfully";

    // Redirect to login page
    header("Location: login.php");
    exit(0);
}

if (isset($_POST['check_Emailbtn'])) {
    $email = $_POST['email'];
    $checkemail =   "SELECT email from users WHERE email = '$email'";
    $checkemail_run = mysqli_query($con, $checkemail);
    if (mysqli_num_rows($checkemail_run) > 0) {
      echo "Email Id already taken.!";
    } else {
        echo "It's available";
    }
}

if (isset($_POST['addUser'])) {
    // --- Sanitize Input ---
    $personnel_no     = htmlspecialchars(trim($_POST['personnel_no']), ENT_QUOTES, 'UTF-8');
    $rank_code        = preg_replace("/[^0-9]/", "", $_POST['rank_code']);
    $full_name_en     = htmlspecialchars(trim($_POST['full_name_en']), ENT_QUOTES, 'UTF-8');
    $full_name_ne     = htmlspecialchars(trim($_POST['full_name_ne']), ENT_QUOTES, 'UTF-8');
    $unit_id          = preg_replace("/[^0-9]/", "", $_POST['unit_id']);
    $phone            = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');
    $email            = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password         = trim($_POST['password']);
    $confirmpassword  = trim($_POST['confirmpassword']);
    $image            = $_FILES['image']['name'] ?? '';

    // --- Validate Input ---
    if (!preg_match("/^(98[0-4]|985|986)\d{7}$/", $phone)) {
        $_SESSION['status'] = "Invalid Nepali phone number. Only Ncell (980-984) or Namaste/NTC (985-986) numbers allowed.";
        $_SESSION['msg_type'] = "info";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Invalid email format";
        $_SESSION['msg_type'] = "info";
    } elseif ($password !== $confirmpassword) {
        $_SESSION['status'] = "Password and Confirm Password do not match";
        $_SESSION['msg_type'] = "info";
    } else {
        // --- Check if email already exists ---
        $checkStmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $_SESSION['status'] = "Email ID is already taken";
        } else {
            // --- Handle Image Upload ---
            $upload_dir = "profile/";
            $new_image_name = null;

            if (!empty($image)) {
                $tmp_name = $_FILES['image']['tmp_name'];
                $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($ext, $allowed)) {
                    $new_image_name = 'user_' . date('Ymd_His') . '_' . uniqid() . '.' . $ext;
                    move_uploaded_file($tmp_name, $upload_dir . $new_image_name);
                } else {
                    $_SESSION['status'] = "Invalid image type. Only JPG, JPEG, PNG, and GIF allowed.";
                    header('Location: user_registration.php');
                    exit;
                }
            }

            // --- Hash Password ---
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // --- Insert User ---
            $stmt = $con->prepare("INSERT INTO users 
                (personnel_no, rank_code, full_name_en, full_name_ne, unit_id, phone, email, password, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param(
                "iississss",
                $personnel_no,
                $rank_code,
                $full_name_en,
                $full_name_ne,
                $unit_id,
                $phone,
                $email,
                $password_hashed,
                $new_image_name
            );

            if ($stmt->execute()) {
                $_SESSION['status'] = "User added successfully.";
            } else {
                $_SESSION['status'] = "Failed to add user. Error: " . $stmt->error;
            }
        }
    }

    header('Location: user_registration.php');
    exit;
}

if (isset($_POST['updateUser'])) {
    // Step 1: Sanitize and get form data
    $user_id = intval($_POST['user_id']);
    $personnel_no = htmlspecialchars(trim($_POST['personnel_no']), ENT_QUOTES, 'UTF-8');
    $rank_code = intval($_POST['rank_code']);  // Rank code is already an integer
    $full_name_en = htmlspecialchars(trim($_POST['full_name_en']), ENT_QUOTES, 'UTF-8');
    $full_name_ne = htmlspecialchars(trim($_POST['full_name_ne']), ENT_QUOTES, 'UTF-8');
    $phone = preg_replace("/[^0-9]/", "", $_POST['phone']);  // Ensure phone contains only numbers
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $role_as = intval($_POST['role_as']);  // Ensure role_as is an integer
    $unit_id = intval($_POST['unit_id']);  // Ensure unit_id is an integer

    // Step 2: Update user data in the database (without image or password update)
    $stmt = $con->prepare("UPDATE users SET personnel_no=?, full_name_en=?, full_name_ne=?, phone=?, email=?, role_as=?, rank_code=?, unit_id=? WHERE id=?");
    $stmt->bind_param("ssssssiii", $personnel_no, $full_name_en, $full_name_ne, $phone, $email, $role_as, $rank_code, $unit_id, $user_id);

    // Step 3: Check if the update was successful
    if ($stmt->execute()) {
        $_SESSION['status'] = "User updated successfully";
        header('Location: user_registration.php');  // Redirect after successful update
        exit;
    } else {
        $_SESSION['status'] = "Error: User not updated successfully";
        header('Location: registered_edit.php?id=' . $user_id);  // Redirect back to the edit page
        exit;
    }
}

// Delete Room
if (isset($_POST['DeleteUserBtn'])) {
    $user_id = intval($_POST['delete_id']); // Get the user ID from the POST data
    // --- Step 2: Soft delete user by updating is_void column to 1 ---
    $query = "UPDATE users SET is_void = 1 WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['status'] = "User deleted successfully";
        header('Location: user_registration.php');
        exit;
    } else {
        $_SESSION['status'] = "User not deleted successfully";
        header('Location: user_registration.php');
        exit;
    }
}

// --- Activate User ---
if (isset($_GET['activate_id'])) {
    $user_id = intval($_GET['activate_id']);
    // Check current status
    $stmtCheck = $con->prepare("SELECT is_active FROM users WHERE id = ?");
    $stmtCheck->bind_param("i", $user_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        if ($user['is_active'] == 1) {
            $_SESSION['status'] = "User is already activated.";
        } else {
            // Activate user
            $stmt = $con->prepare("UPDATE users SET is_active=1 WHERE id=?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['status'] = "User activated successfully.";
            } else {
                $_SESSION['status'] = "Error activating user: " . $stmt->error;
            }
        }
    } else {
        $_SESSION['status'] = "User not found.";
    }
    header("Location: user_registration.php");
    exit();
}

// --- Deactivate User ---
if (isset($_GET['deactivate_id'])) {
    $user_id = intval($_GET['deactivate_id']);

    // Check current status
    $stmtCheck = $con->prepare("SELECT is_active FROM users WHERE id = ?");
    $stmtCheck->bind_param("i", $user_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['is_active'] == 0) {
            $_SESSION['status'] = "User is already deactivated.";
        } else {
            // Deactivate user
            $stmt = $con->prepare("UPDATE users SET is_active=0 WHERE id=?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['status'] = "User deactivated successfully.";
            } else {
                $_SESSION['status'] = "Error deactivating user: " . $stmt->error;
            }
        }
    } else {
        $_SESSION['status'] = "User not found.";
    }

    header("Location: user_registration.php");
    exit();
}

// --- Activate Report Permission ---
if (isset($_GET['activate_report_id'])) {
    $user_id = intval($_GET['activate_report_id']);
    // Check current status
    $stmtCheck = $con->prepare("SELECT is_report FROM users WHERE id = ?");
    $stmtCheck->bind_param("i", $user_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        if ($user['is_report'] == 1) {
            $_SESSION['status'] = "User report permission is already activated.";
        } else {
            // Activate report permission
            $stmt = $con->prepare("UPDATE users SET is_report=1 WHERE id=?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['status'] = "User report permission activated successfully.";
            } else {
                $_SESSION['status'] = "Error activating user report permission: " . $stmt->error;
            }
        }
    } else {
        $_SESSION['status'] = "User not found.";
    }
    header("Location: user_registration.php");
    exit();
}

// --- Deactivate Report Permission ---
if (isset($_GET['deactivate_report_id'])) {
    $user_id = intval($_GET['deactivate_report_id']);

    // Check current status
    $stmtCheck = $con->prepare("SELECT is_report FROM users WHERE id = ?");
    $stmtCheck->bind_param("i", $user_id);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if ($user['is_report'] == 0) {
            $_SESSION['status'] = "User report permission is already deactivated.";
        } else {
            // Deactivate report permission
            $stmt = $con->prepare("UPDATE users SET is_report=0 WHERE id=?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['status'] = "User report permission deactivated successfully.";
            } else {
                $_SESSION['status'] = "Error deactivating user report permission: " . $stmt->error;
            }
        }
    } else {
        $_SESSION['status'] = "User not found.";
    }

    header("Location: user_registration.php");
    exit();
}

// --- Reset Password ---
if (isset($_GET['reset_id'])) {
    $user_id = intval($_GET['reset_id']);
    $newPassword = "test@123";
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $con->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $hashedPassword, $user_id);
    if ($stmt->execute()) {
        $_SESSION['status'] = "Password reset successfully.";
    } else {
        $_SESSION['status'] = "Error resetting password: " . $stmt->error;
    }
    header("Location: user_registration.php");
    exit();
}
?>