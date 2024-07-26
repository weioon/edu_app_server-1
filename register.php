<?php
// Allow CORS
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mb_edu_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$email = $_POST['email'];
$student_id = $_POST['student_id'];
$password = $_POST['password'];
$phone_number = $_POST['phone_number'];
$address = $_POST['address'];
$security_answer = $_POST['security_answer'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (email, student_id, password, phone_number, address, security_answer) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $email, $student_id, $password, $phone_number, $address, $security_answer);

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