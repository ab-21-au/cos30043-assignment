<?php
require __DIR__ . '/db.php';

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = isset($data['username']) ? trim($data['username']) : '';
$password = isset($data['password']) ? trim($data['password']) : '';
$email    = isset($data['email']) ? trim($data['email']) : '';

if (empty($username) || empty($password) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'All registration fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address structure (e.g., name@domain.com).']);
    exit;
}

if (strlen($username) < 3) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Username must be at least 3 characters long.']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Password must be at least 6 characters long.']);
    exit;
}



mysqli_report(MYSQLI_REPORT_OFF);
$conn = connectDatabase();

$check_stmt = mysqli_prepare($conn, 'SELECT account_id FROM MRS_Account WHERE username = ? OR email = ? LIMIT 1');
mysqli_stmt_bind_param($check_stmt, 'ss', $username, $email);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) > 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Username or Email address is already registered.']);
    mysqli_stmt_close($check_stmt);
    mysqli_close($conn);
    exit;
}
mysqli_stmt_close($check_stmt);

$password_hash = password_hash($password, PASSWORD_BCRYPT);
$insert_stmt = mysqli_prepare($conn, 'INSERT INTO MRS_Account (username, email, password_hash) VALUES (?, ?, ?)');
mysqli_stmt_bind_param($insert_stmt, 'sss', $username, $email, $password_hash);

if (mysqli_stmt_execute($insert_stmt)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database operation failed during insertion.']);
}

mysqli_stmt_close($insert_stmt);
mysqli_close($conn);