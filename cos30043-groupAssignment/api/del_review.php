<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Get data w/ fetch
$data = json_decode(file_get_contents('php://input'), true);
$review_id = isset($data['review_id']) ? intval($data['review_id']) : 0;
$account_id = isset($data['account_id']) ? intval($data['account_id']) : 0;

if ($review_id <= 0 || $account_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid structural payload IDs']);
    exit;
}

$conn = connectDatabase();

//del only if id + acc id match
$sql = "DELETE FROM MRS_Review WHERE review_id = ? AND account_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $review_id, $account_id);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Review not found or unauthorized deletion']);
    }
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database execution failure']);
}

mysqli_close($conn);
?>