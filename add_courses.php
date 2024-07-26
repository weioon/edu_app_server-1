<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "mb_edu_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Retrieve JSON input
$input = json_decode(file_get_contents('php://input'), true);
$sessionToken = $input['sessionToken'] ?? '';
$courseName = $input['name'] ?? '';
$schedule = $input['schedule'] ?? '';

// Log the received data
error_log("Received sessionToken: " . var_export($sessionToken, true));
error_log("Received courseName: " . var_export($courseName, true));
error_log("Received schedule: " . var_export($schedule, true));

// Validate input
if (empty($sessionToken) || empty($courseName) || empty($schedule)) {
    $errorMessage = sprintf(
        "Invalid input: sessionToken - %s, courseName - %s, schedule - %s",
        var_export($sessionToken, true),
        var_export($courseName, true),
        var_export($schedule, true)
    );
    error_log($errorMessage, 3, 'error.log'); // Specify the log file directly
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

// Insert into user_courses table
$stmt = $conn->prepare("INSERT INTO user_courses (id, name, schedule) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $sessionToken, $courseName, $schedule);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Course added successfully']);
} else {
    $error_message = $stmt->error;
    error_log("Failed to add course: " . $error_message);
    echo json_encode(['status' => 'error', 'message' => 'Failed to add course', 'error' => $error_message]);
}

$stmt->close();
$conn->close();
?>