<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

$query = $db->prepare("SELECT id, password FROM users WHERE email = ?");
$query->execute([$email]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'userId' => $user['id']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
?>
