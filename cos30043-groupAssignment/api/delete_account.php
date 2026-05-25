<?php

require __DIR__ . '/db.php';


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Ensure this endpoint only responds to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method Not Allowed. Use POST."]);
    exit();
}

// Read the raw JSON payload passed from Vue
$inputData = json_decode(file_get_contents("php://input"), true);
$username  = isset($inputData['username']) ? trim($inputData['username']) : '';


if (empty($username)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Incomplete request. Username is required for deletion."]);
    exit();
}


$conn = connectDatabase();


$sql = "DELETE FROM MRS_Account WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    // Check if an actual record matched that username string and was deleted
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Account associated with '" . $username . "' was permanently deleted successfully."
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "error" => "Account record not found or already deleted."
        ]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Failed to prepare database deletion statement."
    ]);
}


mysqli_close($conn);
