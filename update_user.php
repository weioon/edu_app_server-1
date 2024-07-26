<?php
ob_end_clean();

// Start output buffering
ob_start();

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if all required fields are provided
$required_fields = ['email', 'name', 'student_id', 'phone_number', 'address', 'security_answer'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        die(json_encode([
            "status" => "error",
            "message" => "$field is required"
        ]));
    }
}

// Sanitize input
$email = $conn->real_escape_string($_POST['email']);
$name = $conn->real_escape_string($_POST['name']);
$student_id = $conn->real_escape_string($_POST['student_id']);
$phone_number = $conn->real_escape_string($_POST['phone_number']);
$address = $conn->real_escape_string($_POST['address']);
$security_answer = $conn->real_escape_string($_POST['security_answer']);

// Prepare SQL statement
$sql = "UPDATE users SET name = ?, student_id = ?, phone_number = ?, address = ?, security_answer = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $student_id, $phone_number, $address, $security_answer, $email);

// Execute the statement
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "User data updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No changes made or user not found"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error updating user data: " . $stmt->error
    ]);
}

// Close statement and connection
$stmt->close();
$conn->close();


// Capture the output
$output = ob_get_clean();

// If there was any output before the JSON, it's probably an error
if (!empty($output)) {
    echo json_encode([
        "status" => "error",
        "message" => "PHP Error: " . $output
    ]);
} else {
    // Your existing JSON response here
}
?>