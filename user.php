<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mb_edu_app";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userId'])) {
    $userId = $_GET['userId'];
    $query = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $query->bind_param("i", $userId);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }
    $query->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $studentId = $data['student_id'];
    $password = $data['password'];
    $contactNumber = $data['phone_number'];
    $address = $data['address'];
    $query = $conn->prepare("UPDATE users SET name = ?, email = ?, student_id = ?, password = ?, phone_number = ?, address = ? WHERE id = ?");
    $query->bind_param("ssssssi", $name, $email, $studentId, $password, $contactNumber, $address, $userId);
    $success = $query->execute();
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $query->error]);
    }
    $query->close();
}
$conn->close();
?>