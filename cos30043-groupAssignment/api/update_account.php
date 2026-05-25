<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once 'db.php';

$raw_json = file_get_contents('php://input');
$data = json_decode($raw_json, true);

// val before connect
if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Empty JSON data']);
    exit;
}

// extract and validate fields
$account_id       = isset($data['account_id']) ? intval($data['account_id']) : 0;
$username         = isset($data['username']) ? trim($data['username']) : '';
$email            = isset($data['email']) ? trim($data['email']) : '';
$current_password = isset($data['current_password']) ? $data['current_password'] : '';
$new_password     = isset($data['new_password']) ? $data['new_password'] : '';

//checker for missing fields
if ($account_id <= 0 || $username === '' || $email === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required account fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid email address']);
    exit;
}

$is_password_update = ($current_password !== '' || $new_password !== '');

if ($is_password_update && ($current_password === '' || $new_password === '')) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Current and new password are required']);
    exit;
}

$conn = connectDatabase();

// check current password before updating to a new password
if ($is_password_update) {
    $select_sql = "SELECT password_hash FROM MRS_Account WHERE account_id = ?";
    $select_stmt = mysqli_prepare($conn, $select_sql);

    if (!$select_stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Database query failed']);
        mysqli_close($conn);
        exit;
    }

    mysqli_stmt_bind_param($select_stmt, "i", $account_id);
    mysqli_stmt_execute($select_stmt);
    $result = mysqli_stmt_get_result($select_stmt);
    $account = mysqli_fetch_assoc($result);
    mysqli_stmt_close($select_stmt);

    if (!$account) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Account not found']);
        mysqli_close($conn);
        exit;
    }

    if (!password_verify($current_password, $account['password_hash'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
        mysqli_close($conn);
        exit;
    }

    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE MRS_Account
            SET username = ?, email = ?, password_hash = ?
            WHERE account_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $password_hash, $account_id);
    }
} else {
    $sql = "UPDATE MRS_Account
            SET username = ?, email = ?
            WHERE account_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $account_id);
    }
}

//inject safely into db
if ($stmt) {
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Account updated successfully']);
    } else {
        $status = mysqli_errno($conn) === 1062 ? 409 : 500;
        http_response_code($status);
        echo json_encode(['success' => false, 'error' => 'Unable to update account']);
    }

    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>
