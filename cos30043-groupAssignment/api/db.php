<?php

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

    $conn = mysqli_connect(
        $host ?? '',
        $username ?? '',
        $password ?? '',
        $database ?? ''
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
