<?php
session_start();
include('config/dbcon.php');
date_default_timezone_set('Asia/Kathmandu');

if (isset($_POST['registerUser'])) {

    // Debug: check if form reached
    // echo "Reached registerUser block!"; exit;

    // Sanitize input
    $personnel_no    = htmlspecialchars(trim($_POST['personnel_no']), ENT_QUOTES, 'UTF-8');
    $rank_code       = preg_replace("/[^0-9]/", "", $_POST['rank_code']);
    $full_name_en    = htmlspecialchars(trim($_POST['full_name_en']), ENT_QUOTES, 'UTF-8');
    $full_name_ne    = htmlspecialchars(trim($_POST['full_name_ne']), ENT_QUOTES, 'UTF-8');
    $unit_id         = preg_replace("/[^0-9]/", "", $_POST['unit_id']);
    $phone           = htmlspecialchars(trim($_POST['phone']), ENT_QUOTES, 'UTF-8');
    $email           = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password        = trim($_POST['password']);
    $confirmpassword = trim($_POST['confirmpassword']);
    $image           = $_FILES['image']['name'] ?? '';

    // Validate input
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
        // Check if email exists
        $checkStmt = $con->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $_SESSION['status'] = "Email ID is already taken";
        } else {
            // Image upload
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
                    header('Location: register.php');
                    exit;
                }
            }

            // Hash password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
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
                $_SESSION['status'] = "User registered successfully.";
                $_SESSION['msg_type'] = "success";

            } else {
                $_SESSION['status'] = "Failed to register user. Error: " . $stmt->error;
            }
        }
    }

    header('Location: register.php');
    exit;
}
?>
