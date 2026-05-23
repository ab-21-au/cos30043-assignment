<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

require_once 'db.php';

// get movie id as int
$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

if ($movie_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid Movie ID']);
    exit;
}

$conn = connectDatabase();

// get review tables from movie id
$sql = "SELECT r.review_id, r.rating, r.rating_plot, r.rating_acting, r.rating_pacing, 
               r.review_title, r.review_text, r.rewatch_status, r.met_expectations, 
               r.created_at, a.username 
        FROM MRS_Review r
        JOIN MRS_Account a ON r.account_id = a.account_id
        WHERE r.tmdb_movie_id = ?
        ORDER BY r.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $movie_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = [
            'review_id' => $row['review_id'],
            'username' => $row['username'],
            'title' => $row['review_title'],
            'content' => $row['review_text'],
            'rating' => $row['rating'],
            'created_at' => $row['created_at'],
            'plot'          => $row['rating_plot'],
            'acting'        => $row['rating_acting'],
            'pacing'        => $row['rating_pacing'],
            'rewatch'       => $row['rewatch_status'],
            'expectations'  => $row['met_expectations']
        ];
    }
    
    // pass to frontend as json
    echo json_encode($reviews);
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>