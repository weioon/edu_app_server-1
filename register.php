<?php
// Allow CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mb_edu_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Get the data
$name = $data['name'] ?? null;
$email = $data['email'] ?? null;
$student_id = $data['student_id'] ?? null;
$password = $data['password'] ?? null;
$phone_number = $data['phone_number'] ?? null;
$address = $data['address'] ?? null;
$security_answer = $data['security_answer'] ?? null;

// Validate that all required fields are present
if (!$name || !$email || !$student_id || !$password || !$phone_number || !$address || !$security_answer) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Hash the password with SHA-1
$hashed_password = sha1($password);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (name, email, student_id, password, phone_number, address, security_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $email, $student_id, $hashed_password, $phone_number, $address, $security_answer);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User registered successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>