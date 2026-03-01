<?php
session_start();
include 'includes/header.php';
if (isset($_SESSION['auth'])) {
    $_SESSION['status'] = "You are already logged in";  
    header('Location: index.php');
    exit;
}
?>

<style>
/* Body Background */
body {
    background: url('images/background.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
}

body::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1;
}

/* Login Box */
.login-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 600px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 40px 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Header */
.login-header h4 {
    text-align: center;
    color: #579b49;
    font-weight: 700;
    font-size: 1.3rem;
    text-transform: uppercase;
    margin-bottom: 15px;
    font-family: 'Orbitron', sans-serif;
}

.login-header span {
    display: block;
    font-size: 1rem;
    color: #579b49;
    margin-top: 5px;
    font-family: 'Poppins', sans-serif;
}

/* Inputs */
.form-control {
    border-radius: 10px;
    padding: 12px 15px 12px 45px;
    font-size: 14px;
    border: 1px solid #ced4da;
    margin-bottom: 18px;
    width: 100%;
}

.form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 10px rgba(74,144,226,0.2);
    outline: none;
}

/* Input Icons */
.icon-input {
    position: relative;
}
.icon-input i {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #999;
}

/* Buttons */
.btn-custom {
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    font-size: 15px;
    background: #4a90e2;
    border: none;
    color: #fff;
    cursor: pointer;
    width: 100%;
    margin-bottom: 12px;
}

.btn-custom:hover {
    background: #357ABD;
}

/* Register button styled same as login */
.btn-register {
    display: block;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    font-size: 15px;
    background: #c654aa;
    color: #fff;
    text-decoration: none;
    text-align: center;
    width: 100%;
}

.btn-register:hover {
    background: #7b2020;
}

/* Import fonts */
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Poppins:wght@400&display=swap');
</style>

<?php include 'message.php'; ?>

<div class="login-wrapper">
    <div class="login-header">
        <h4>
            Server Management System
            <span>सर्भर व्यवस्थापन प्रणाली</span>
        </h4>
    </div>

    <form method="post" action="logincode.php">
        <div class="icon-input">
            <i class="fas fa-envelope"></i>
            <input type="email" class="form-control" name="email" placeholder="तपाईंको इमेल लेख्नुहोस्" required autofocus>
        </div>
        <div class="icon-input">
            <i class="fas fa-lock"></i>
            <input type="password" class="form-control" name="password" placeholder="तपाईंको पासवर्ड लेख्नुहोस्" required>
        </div>

        <button type="submit" name="login_btn" class="btn btn-custom">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>

        <!-- Register as button -->
        <a href="register.php" class="btn-register">Register गर्नुहोस्</a>
    </form>
</div>

<?php include 'includes/script.php'; ?>
<script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>
