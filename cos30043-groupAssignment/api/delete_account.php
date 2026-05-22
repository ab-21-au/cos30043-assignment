<?php
// Reuse your existing database connection file
require __DIR__ . '/db.php';

// Cross-Origin headers so your local Vue development server can talk to Apache
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

// Handle preflight OPTIONS requests gracefully
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Ensure this endpoint only responds to POST requests for safety
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method Not Allowed. Use POST."]);
    exit();
}

// Read the raw JSON payload passed from Vue
$inputData = json_decode(file_get_contents("php://input"), true);
$username  = isset($inputData['username']) ? trim($inputData['username']) : '';

// Validation guard: Verify that a username parameter actually arrived
if (empty($username)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Incomplete request. Username is required for deletion."]);
    exit();
}

// Establish the MySQLi connection using your team's file configuration
$conn = connectDatabase();

// Use MySQLi procedural syntax for the prepared statement
$sql = "DELETE FROM MRS_Account WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // "s" tells MySQL to expect a String data type variable mapping
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

// Close the active link connection
mysqli_close($conn);