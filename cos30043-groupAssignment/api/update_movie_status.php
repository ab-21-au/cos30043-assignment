<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'error' => 'Method not allowed'));
    exit;
}

session_start();

if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(array('success' => false, 'error' => 'Unauthorized access. Please sign in.'));
    exit;
}

require_once __DIR__ . '/db.php';
$conn = connectDatabase();

$data = json_decode(file_get_contents('php://input'), true);
$user_movie_id = isset($data['user_movie_id']) ? intval($data['user_movie_id']) : 0;
$status = isset($data['status']) ? trim($data['status']) : '';

$valid_statuses = array('want_to_watch', 'watching', 'watched');

if ($user_movie_id <= 0 || !in_array($status, $valid_statuses, true)) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Invalid movie status update.'));
    mysqli_close($conn);
    exit;
}

$account_stmt = mysqli_prepare($conn, "SELECT account_id FROM MRS_Account WHERE username = ? LIMIT 1");

if (!$account_stmt) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Database query failed.'));
    mysqli_close($conn);
    exit;
}

mysqli_stmt_bind_param($account_stmt, "s", $_SESSION['username']);
mysqli_stmt_execute($account_stmt);
$account_result = mysqli_stmt_get_result($account_stmt);
$account = mysqli_fetch_assoc($account_result);
mysqli_stmt_close($account_stmt);

if (!$account) {
    http_response_code(404);
    echo json_encode(array('success' => false, 'error' => 'User account record not found.'));
    mysqli_close($conn);
    exit;
}

$account_id = intval($account['account_id']);

$stmt = mysqli_prepare(
    $conn,
    "UPDATE MRS_UserMovieList
     SET status = ?, updated_at = CURRENT_TIMESTAMP
     WHERE user_movie_id = ? AND account_id = ?"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(array('success' => false, 'error' => 'Database query failed.'));
    mysqli_close($conn);
    exit;
}

mysqli_stmt_bind_param($stmt, "sii", $status, $user_movie_id, $account_id);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(array('success' => true, 'status' => $status));
} else {
    http_response_code(404);
    echo json_encode(array('success' => false, 'error' => 'Movie list item not found.'));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
