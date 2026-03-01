<?php
// Autoload Composer packages
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load the .env file from project root. From your this file to root directory.
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad(); // safeLoad() avoids errors if .env is missing

/**
 * env() helper function
 * Fetch environment variables with optional default value
 *
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env($key, $default = null) {
    // Check $_ENV first
    if (isset($_ENV[$key])) return $_ENV[$key];
    // Then check getenv()
    $value = getenv($key);
    if ($value !== false) return $value;
    // Return default if not found
    return $default;
}
