<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db.php';

$account_id = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$favourites_only = isset($_GET['favourites_only']) ? intval($_GET['favourites_only']) : 0;

if ($account_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid Account ID']);
    exit;
}

$valid_statuses = ['want_to_watch', 'watching', 'watched'];

if ($status !== '' && !in_array($status, $valid_statuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid movie list status']);
    exit;
}

$conn = connectDatabase();

$sql = "SELECT user_movie_id, account_id, tmdb_movie_id, status, is_favourite, created_at, updated_at
        FROM MRS_UserMovieList
        WHERE account_id = ?";

if ($status !== '') {
    $sql .= " AND status = ?";
}

if ($favourites_only === 1) {
    $sql .= " AND is_favourite = 1";
}

$sql .= " ORDER BY updated_at DESC, created_at DESC";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    if ($status !== '') {
        mysqli_stmt_bind_param($stmt, "is", $account_id, $status);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $account_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $movies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $movies[] = [
            'user_movie_id' => intval($row['user_movie_id']),
            'account_id' => intval($row['account_id']),
            'tmdb_movie_id' => intval($row['tmdb_movie_id']),
            'status' => $row['status'],
            'is_favourite' => boolval($row['is_favourite']),
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ];
    }

    echo json_encode(['success' => true, 'movies' => $movies]);
    mysqli_stmt_close($stmt);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database query failed']);
}

mysqli_close($conn);
?>
