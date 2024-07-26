<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mb_edu_app";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$id = $_POST['id'];
$feedback = $_POST['feedback'];

$photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo = file_get_contents($_FILES['photo']['tmp_name']);
}

$stmt = $conn->prepare("INSERT INTO user_feedback (user_id, feedback, photo) VALUES (?, ?, ?)");
$stmt->bind_param("ssb", $id, $feedback, $photo);

if (!$stmt->execute()) {
    error_log("MySQL Error: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Failed to submit feedback: ' . $stmt->error]);
} else {
    echo json_encode(['success' => true, 'message' => 'Feedback submitted successfully']);
}

$stmt->close();
$conn->close();
?>