<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');


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
$account_id       = isset($data['account_id']) ? intval($data['account_id']) : 0;
$tmdb_movie_id    = isset($data['tmdb_movie_id']) ? intval($data['tmdb_movie_id']) : 0;
$rating_plot      = isset($data['rating_plot']) ? intval($data['rating_plot']) : 0;
$rating_acting    = isset($data['rating_acting']) ? intval($data['rating_acting']) : 0;
$rating_pacing    = isset($data['rating_pacing']) ? intval($data['rating_pacing']) : 0;
$rating           = isset($data['rating']) ? trim($data['rating']) : '';
$review_text      = isset($data['review_text']) ? trim($data['review_text']) : '';
$rewatch_status   = isset($data['rewatch_status']) ? trim($data['rewatch_status']) : '';
$met_expectations = isset($data['met_expectations']) ? trim($data['met_expectations']) : '';

//checker for missing fields
if ($account_id <= 0 || $tmdb_movie_id <= 0 || empty($rating) || empty($review_text)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields. Fill out your entire review form.']);
    exit;
}

$conn = connectDatabase();

$sql = "INSERT INTO MRS_Review (account_id, tmdb_movie_id, rating_plot, rating_acting, rating_pacing, rating, review_text, rewatch_status, met_expectations) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

//inject safely into db
if ($stmt) {
    mysqli_stmt_bind_param(
        $stmt, 
        "iiiiissss", 
        $account_id, 
        $tmdb_movie_id, 
        $rating_plot, 
        $rating_acting, 
        $rating_pacing, 
        $rating, 
        $review_text, 
        $rewatch_status, 
        $met_expectations
    );

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true, 'message' => 'Review logged successfully']);
    } else {
        http_response_code(409); 
        echo json_encode(['success' => false, 'error' => 'Unable to upload You can only review a film o.nce!']);
    }
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error, database query fail']);
}

mysqli_close($conn);
?>