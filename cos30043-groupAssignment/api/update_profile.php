<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();
require_once __DIR__ . '/db.php'; 
$conn = connectDatabase();

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

$current_username = $_SESSION['username'];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT account_id, username, email FROM MRS_Account WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $current_username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result) {
        echo json_encode([
            'success' => true, 
            'account_id' => (int)$result['account_id'],
            'username' => $result['username'], 
            'email' => $result['email']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Account details not found.']);
    }
    $conn->close();
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data provided.']);
    exit;
}

$new_username   = isset($data['username']) ? trim($data['username']) : '';
$email          = isset($data['email']) ? trim($data['email']) : '';

if (empty($new_username) || empty($email)) {
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare("UPDATE MRS_Account SET username = ?, email = ? WHERE username = ?");
$stmt->bind_param("sss", $new_username, $email, $current_username);

if ($stmt->execute()) {
    $_SESSION['username'] = $new_username;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database update failed.']);
}

$stmt->close();
$conn->close();
