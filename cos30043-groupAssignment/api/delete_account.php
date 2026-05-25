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
$account_id = isset($data['account_id']) ? intval($data['account_id']) : 0;

//checker for missing fields
if ($account_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid Account ID']);
    exit;
}

$conn = connectDatabase();

$sql = "DELETE FROM MRS_Account WHERE account_id = ?";
$stmt = mysqli_prepare($conn, $sql);

//inject safely into db
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $account_id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Account not found']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Unable to delete account']);
    }

    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>
