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

try {
    
    $sql = "SELECT 
                r.review_id,
                r.tmdb_movie_id,
                r.rating_plot,
                r.rating_acting,
                r.rating_pacing,
                r.rating,
                r.rewatch_status,
                r.met_expectations,
                r.review_title,
                r.review_text,
                r.contains_spoilers,
                r.created_at
            FROM MRS_Review r
            JOIN MRS_Account a ON r.account_id = a.account_id
            WHERE a.username = :username
            ORDER BY r.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    echo json_encode([
        "success" => true,
        "reviews" => $reviews
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Database error occurred while fetching reviews: " . $e->getMessage()
    ]);
}