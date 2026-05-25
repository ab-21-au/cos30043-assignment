<?php

if (!defined('PASSWORD_BCRYPT')) {
    define('PASSWORD_BCRYPT', 1);
}

if (!defined('PASSWORD_DEFAULT')) {
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
}

if (!function_exists('password_hash')) {
    function password_hash($password, $algo, $options = array()) {
        if ($algo !== PASSWORD_BCRYPT) {
            return false;
        }

        $cost = isset($options['cost']) ? intval($options['cost']) : 10;
        $cost = max(4, min(31, $cost));

        $raw_salt = '';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $raw_salt = openssl_random_pseudo_bytes(16);
        }

        if ($raw_salt === false || strlen($raw_salt) < 16) {
            $raw_salt = '';
            for ($i = 0; $i < 16; $i++) {
                $raw_salt .= chr(mt_rand(0, 255));
            }
        }

        $salt = substr(strtr(base64_encode($raw_salt), '+', '.'), 0, 22);
        return crypt($password, sprintf('$2y$%02d$%s$', $cost, $salt));
    }
}

if (!function_exists('password_verify')) {
    function password_verify($password, $hash) {
        $calculated_hash = crypt($password, $hash);

        if (function_exists('hash_equals')) {
            return hash_equals($hash, $calculated_hash);
        }

        if (strlen($hash) !== strlen($calculated_hash)) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < strlen($hash); $i++) {
            $result |= ord($hash[$i]) ^ ord($calculated_hash[$i]);
        }

        return $result === 0;
    }
}

function sendDatabaseError() {
    http_response_code(500);
    header('Content-Type: application/json');

    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed.',
    ]);

    exit;
}

// Check for the settings.php for either local dev environment or mercury server.
function loadDatabaseConfigPath() {
    $configPaths = [
        __DIR__ . '/../../../data/settings.php',
        __DIR__ . '/../database/settings.php',
    ];

    foreach ($configPaths as $path) {
        if (is_readable($path)) {
            return $path;
        }
    }

    sendDatabaseError();
}

// Database connection helper function
function connectDatabase() {
    require loadDatabaseConfigPath();

    // $conn = mysqli_connect(
    $db_host     = isset($host) ? $host : '';
    $db_user     = isset($username) ? $username : '';
    $db_pass     = isset($password) ? $password : '';
    $db_name     = isset($database) ? $database : '';
    
    $conn = mysqli_connect(
        $db_host,
        $db_user,
        $db_pass,
        $db_name
    );

    if (!$conn) {
        sendDatabaseError();
    }

    if (!mysqli_set_charset($conn, 'utf8mb4')) {
        mysqli_close($conn);
        sendDatabaseError();
    }

    return $conn;
}
