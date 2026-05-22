<?php
require __DIR__ . '/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Missing username parameter."]);
    exit();
}

$username = trim($_GET['username']);

try {
    // Count records where status is strictly 'watched' for this user
    $sql = "SELECT COUNT(*) AS total_watched 
            FROM MRS_UserMovieList m
            JOIN MRS_Account a ON m.account_id = a.account_id
            WHERE a.username = :username AND m.status = 'watched'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "count" => (int)$result['total_watched']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Database error: " . $e->getMessage()
    ]);
}