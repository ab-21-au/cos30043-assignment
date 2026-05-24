PHP
<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

session_start();

require_once __DIR__ . '/db.php'; 
$conn = connectDatabase();


if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access. Please sign in.']);
    exit;
}

$current_username = $_SESSION['username'];


$account_stmt = $conn->prepare("SELECT account_id FROM MRS_Account WHERE username = ? LIMIT 1");
$account_stmt->bind_param("s", $current_username);
$account_stmt->execute();
$account_result = $account_stmt->get_result()->fetch_assoc();
$account_stmt->close();

if (!$account_result) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'User account record not found.']);
    exit;
}

$account_id = $account_result['account_id'];


$data = json_decode(file_get_contents('php://input'), true);
$tmdb_movie_id = isset($data['tmdb_movie_id']) ? intval($data['tmdb_movie_id']) : 0;

if ($tmdb_movie_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid or missing TMDB Movie ID.']);
    exit;
}


$query = "
    INSERT INTO MRS_UserMovieList (account_id, tmdb_movie_id, is_favourite) 
    VALUES (?, ?, TRUE)
    ON DUPLICATE KEY UPDATE is_favourite = TRUE, updated_at = CURRENT_TIMESTAMP
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $account_id, $tmdb_movie_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Movie added to favorites successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database operation failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();

/* PHP
<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

session_start();

require_once __DIR__ . '/db.php'; 
$conn = connectDatabase();

// 1. Enforce user authentication check
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized access. Please sign in.']);
    exit;
}

$current_username = $_SESSION['username'];

// 2. Fetch the client's internal account_id from the session username
$account_stmt = $conn->prepare("SELECT account_id FROM MRS_Account WHERE username = ? LIMIT 1");
$account_stmt->bind_param("s", $current_username);
$account_stmt->execute();
$account_result = $account_stmt->get_result()->fetch_assoc();
$account_stmt->close();

if (!$account_result) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'User account record not found.']);
    exit;
}

$account_id = $account_result['account_id'];

// 3. Extract payload body elements from frontend fetch stream
$data = json_decode(file_get_contents('php://input'), true);
$tmdb_movie_id = isset($data['tmdb_movie_id']) ? intval($data['tmdb_movie_id']) : 0;

if ($tmdb_movie_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid or missing TMDB Movie ID.']);
    exit;
}

/* 4. Handle Upsert Query execution:
    - If the user/movie combo doesn't exist, insert it as favorited.
    - If the user/movie combination already exists (e.g. marked as 'watched'), 
      trigger the duplicate key constraint and flip its is_favourite state to TRUE.
*/
$query = "
    INSERT INTO MRS_UserMovieList (account_id, tmdb_movie_id, is_favourite) 
    VALUES (?, ?, TRUE)
    ON DUPLICATE KEY UPDATE is_favourite = TRUE, updated_at = CURRENT_TIMESTAMP
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $account_id, $tmdb_movie_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Movie added to favorites successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database operation failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close(); 


//METHOD TO USE
/* 
async toggleFavouriteMovie(movieId) {
  try {
    const response = await fetch('/api/add_favourite.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include', 
      body: JSON.stringify({ tmdb_movie_id: movieId })
    });

    const data = await response.json();

    if (data.success) {
      alert("Added to your favorite films list!");
    } else {
      alert(data.error || "Could not add to favorites.");
    }
  } catch (err) {
    console.error("Network problem matching favorite endpoint:", err);
  }
}
*/