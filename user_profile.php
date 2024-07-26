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

// Check if email is provided
if (!isset($_POST['email'])) {
    die(json_encode([
        "status" => "error",
        "message" => "Email is required"
    ]));
}

$email = $conn->real_escape_string($_POST['email']);

// Prepare SQL statement
$sql = "SELECT name, email, student_id, phone_number, address, security_answer FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the user data
    $user = $result->fetch_assoc();
    
    // Return success response with user data
    echo json_encode([
        "status" => "success",
        "data" => [
            "name" => $user['name'],
            "email" => $user['email'],
            "student_id" => $user['student_id'],
            "phone_number" => $user['phone_number'],
            "address" => $user['address'],
            "security_answer" => $user['security_answer']
        ]
    ]);
} else {
    // No user found with the given email
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>