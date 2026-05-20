<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (!$username || !$email || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

// Avoid raw mysqli exceptions being displayed to the user.
mysqli_report(MYSQLI_REPORT_OFF);
$conn = connectDatabase();

$stmt = mysqli_prepare($conn, '
    INSERT INTO MRS_Account (username, email, password_hash)
    VALUES (?, ?, ?)
');
mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hash);

try {
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        $errno = mysqli_errno($conn);
        $errorMsg = mysqli_error($conn);

        if ($errno === 1062) {
            if (stripos($errorMsg, 'username') !== false) {
                http_response_code(409);
                echo json_encode(['success' => false, 'error' => 'Username already exists']);
            } elseif (stripos($errorMsg, 'email') !== false) {
                http_response_code(409);
                echo json_encode(['success' => false, 'error' => 'Email already exists']);
            } else {
                http_response_code(409);
                echo json_encode(['success' => false, 'error' => 'Duplicate entry']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Unable to create account.']);
        }
    }
} catch (mysqli_sql_exception $e) {
    $errorMsg = $e->getMessage();

    if (stripos($errorMsg, 'Duplicate entry') !== false) {
        http_response_code(409);
        if (stripos($errorMsg, 'username') !== false) {
            echo json_encode(['success' => false, 'error' => 'Username already exists']);
        } elseif (stripos($errorMsg, 'email') !== false) {
            echo json_encode(['success' => false, 'error' => 'Email already exists']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Duplicate entry']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Unable to create account.']);
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn); 