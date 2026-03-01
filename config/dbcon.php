<?php
// Load environment
include_once __DIR__ . '/../supporter/envfxn.php';

// Use env() anywhere
$host = env('DB_HOST');
$user = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$database = env('DB_DATABASE');

// Connect to MySQL
$con = mysqli_connect($host, $user, $password, $database);
$con->set_charset("utf8mb4");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
} else {
    echo "";
}



