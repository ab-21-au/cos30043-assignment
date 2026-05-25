<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;

if ($account_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid Account ID']);
    exit;
}

$conn = connectDatabase();

$sql = "SELECT account_id, username, email, created_at
        FROM MRS_Account
        WHERE account_id = ?";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $account = mysqli_fetch_assoc($result);

    if (!$account) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Account not found']);
    } else {
        echo json_encode([
            'success' => true,
            'account' => [
                'account_id' => intval($account['account_id']),
                'username' => $account['username'],
                'email' => $account['email'],
                'created_at' => $account['created_at'],
            ],
        ]);
    }

    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>
