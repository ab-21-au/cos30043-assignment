<?php
require __DIR__ . '/db.php';

// Cross-Origin headers so local Vue development server can talk to Apache
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header('Content-Type: application/json');

// Handle preflight OPTIONS request from the browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}


if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Missing or invalid username parameter."]);
    exit();
}

$username = trim($_GET['username']);

// 1. Run your team's helper function
$conn = connectDatabase();

// 2. Setup the MySQLi statement to grab this specific user's custom reviews
$sql = "SELECT * FROM MRS_Review WHERE username = ? ORDER BY review_id DESC";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    $reviews = [];
    
    // Loop through the data rows and append them into your collection array
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
    
    echo json_encode([
        "success" => true,
        "reviews" => $reviews
    ]);
    
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Failed to load user reviews collection database."]);
}

mysqli_close($conn);