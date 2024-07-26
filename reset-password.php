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
$newPassword = sha1($data['newPassword']); // Hash the new password with SHA-1

$query = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
$query->bind_param("ss", $newPassword, $email);
$success = $query->execute();

echo json_encode(['success' => $success]);

$query->close();
$conn->close();
?>