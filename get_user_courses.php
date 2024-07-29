<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mb_edu_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the session token from the query parameter
$session_token = isset($_GET['token']) ? $_GET['token'] : '';

if (!$session_token) {
    echo json_encode(['success' => false, 'message' => 'Session token is required']);
    exit;
}

// Get the courses for this specific session token (used as ID)
$sql = "SELECT ucid, id, name, schedule, created_at FROM user_courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $session_token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $courses = [];
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $courses]);
} else {
    echo json_encode(['success' => false, 'message' => 'No courses found for this user']);
}

$stmt->close();
$conn->close();
?>
