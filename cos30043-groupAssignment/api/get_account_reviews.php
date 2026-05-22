<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 

require_once 'db.php';

// get acc id
$account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;

if ($account_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid Account ID']);
    exit;
}

$conn = connectDatabase();


$sql = "SELECT r.review_id, r.tmdb_movie_id, r.rating, r.rating_plot, r.rating_acting, r.rating_pacing, 
               r.review_title, r.review_text, r.rewatch_status, r.met_expectations, 
               r.created_at, a.username 
        FROM MRS_Review r
        JOIN MRS_Account a ON r.account_id = a.account_id
        WHERE r.account_id = ?
        ORDER BY r.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $account_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $reviews = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = [
            'review_id'     => $row['review_id'],
            'tmdb_movie_id' => $row['tmdb_movie_id'],
            'username'      => $row['username'],
            'title'         => $row['review_title'],
            'content'       => $row['review_text'],
            'rating'        => $row['rating'],
            'created_at'    => $row['created_at'],
            'plot'          => $row['rating_plot'],
            'acting'        => $row['rating_acting'],
            'pacing'        => $row['rating_pacing'],
            'rewatch'       => $row['rewatch_status'],
            'expectations'  => $row['met_expectations']
        ];
    }
    
    echo json_encode($reviews);
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>