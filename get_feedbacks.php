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

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// SQL Query to fetch feedbacks
$sql = "SELECT feedback_id, user_id, feedback, photo FROM user_feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

$feedbacks = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Check if photo data exists
        if (!is_null($row['photo'])) {
            $row['photo'] = base64_encode($row['photo']);
        }
        $feedbacks[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $feedbacks]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch feedbacks.']);
}

$conn->close();
