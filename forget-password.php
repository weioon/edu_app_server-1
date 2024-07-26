<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$securityAnswer = $data['securityAnswer'];

// Check if email and security answer match
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND security_answer = ?");
$stmt->bind_param("ss", $email, $securityAnswer);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(['success' => true, 'message' => 'Security answer matched']);
} else {
    echo json_encode(['success' => false, 'message' => 'Email or security answer is incorrect']);
}

$stmt->close();
$conn->close();
?>