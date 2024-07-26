<?php
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
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $instructor = $_POST['instructor'];
    $schedule = $_POST['schedule'];

    $sql = "INSERT INTO courses (course_name, course_code, instructor, schedule) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $course_name, $course_code, $instructor, $schedule);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Course added successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add course"]);
    }

    $stmt->close();
}

$conn->close();
?>