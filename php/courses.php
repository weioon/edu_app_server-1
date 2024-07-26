<?php
require 'db.php';

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Handle OPTIONS request for preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request to fetch courses
    $query = $db->query("SELECT * FROM courses");
    $courses = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $courses]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request to add a new course
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $schedule = $data['schedule'];
    
    // Check for duplicate course
    $query = $db->prepare("SELECT * FROM courses WHERE name = ?");
    $query->execute([$name]);
    $course = $query->fetch(PDO::FETCH_ASSOC);
    if ($course) {
        echo json_encode(['success' => false, 'message' => 'Course already exists']);
        exit;
    }

    // Check for schedule conflict
    $query = $db->prepare("SELECT * FROM courses WHERE schedule = ?");
    $query->execute([$schedule]);
    $course = $query->fetch(PDO::FETCH_ASSOC);
    if ($course) {
        echo json_encode(['success' => false, 'message' => 'Schedule conflict with another course']);
        exit;
    }

    // Insert the new course
    $query = $db->prepare("INSERT INTO courses (name, schedule) VALUES (?, ?)");
    $success = $query->execute([$name, $schedule]);
    echo json_encode(['success' => $success]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle DELETE request to drop a course
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    
    $query = $db->prepare("DELETE FROM courses WHERE name = ?");
    $success = $query->execute([$name]);
    
    if ($success && $query->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete course or course does not exist']);
    }
}
?>