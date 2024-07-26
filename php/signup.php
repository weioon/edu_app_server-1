<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$securityAnswer = $data['securityAnswer']; // Add security answer

$query = $db->prepare("INSERT INTO users (name, email, password, security_answer) VALUES (?, ?, ?, ?)");
$success = $query->execute([$name, $email, $password, $securityAnswer]); // Insert security answer

echo json_encode(['success' => $success]);
?>
