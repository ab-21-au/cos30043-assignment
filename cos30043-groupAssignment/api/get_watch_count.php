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

$conn = connectDatabase();

// 2. Use MySQLi syntax for the prepared statement
$sql = "SELECT COUNT(*) AS watch_count FROM MRS_UserMovieList WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    // Fetch the calculated count out of the result set
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    echo json_encode([
        "success" => true,
        "watch_count" => (int)($row['watch_count'] ?? 0)
    ]);
    
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Failed to fetch user list metrics."]);
}

mysqli_close($conn);