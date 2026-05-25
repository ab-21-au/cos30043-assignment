<?php
require __DIR__ . '/db.php';


header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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
$credential = trim($data['credential'] ?? '');
$password = trim($data['password'] ?? '');

if (!$credential || !$password) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing username/email or password']);
    exit;
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = connectDatabase();

$stmt = mysqli_prepare($conn, '
    SELECT username, email, password_hash
    FROM MRS_Account
    WHERE username = ? OR email = ?
    LIMIT 1
');
mysqli_stmt_bind_param($stmt, 'ss', $credential, $credential);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid username/email or password']);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

if (!password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Invalid username/email or password']);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

session_start();
$_SESSION['username'] = $user['username'];

echo json_encode([
    'success' => true,
    'username' => $user['username'],
]);

mysqli_stmt_close($stmt);
mysqli_close($conn);