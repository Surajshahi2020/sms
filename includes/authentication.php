<?php
session_start();

// If user is not logged in, redirect to login
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "Login to Access Dashboard";
    header('Location: login.php');
    exit(0);
}

// ✅ If logged in, do nothing (allow access)
?>
