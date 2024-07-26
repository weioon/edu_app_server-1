<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Allow cross-origin requests (adjust in production)
header("Content-Type: application/json; charset=UTF-8");

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
    die(json_encode([
        "status" => "error",
        "message" => "Connection failed: " . $conn->connect_error
    ]));
}

// Check if required fields are provided
if (!isset($_POST['email']) || !isset($_POST['feedback'])) {
    die(json_encode([
        "status" => "error",
        "message" => "Email and feedback are required"
    ]));
}

$email = $conn->real_escape_string($_POST['email']);
$feedback = $conn->real_escape_string($_POST['feedback']);

// Handle file upload
$photo_path = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $upload_dir = 'uploads/';
    $photo_name = uniqid() . '_' . basename($_FILES['photo']['name']);
    $photo_path = $upload_dir . $photo_name;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
        // File uploaded successfully
    } else {
        die(json_encode([
            "status" => "error",
            "message" => "Failed to upload file"
        ]));
    }
}

// Prepare SQL statement
$sql = "INSERT INTO user_feedback (email, feedback, photo_path) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $feedback, $photo_path);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Feedback submitted successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error submitting feedback: " . $stmt->error
    ]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>