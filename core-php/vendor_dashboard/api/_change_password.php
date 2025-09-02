<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$current    = $_POST['current_password'] ?? '';
$new        = $_POST['new_password'] ?? '';
$confirm    = $_POST['confirm_password'] ?? '';

$errors = [];
if ($current === '') {
    $errors[] = 'Current password is required';
}
if ($new === '' || strlen($new) < 6) {
    $errors[] = 'New password must be at least 6 characters';
}
if ($new !== $confirm) {
    $errors[] = 'Passwords do not match';
}

if ($errors) {
    echo json_encode(['error' => implode(', ', $errors)]);
    exit;
}

$stmt = $mysqli->prepare('SELECT password FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user || !password_verify($current, $user['password'])) {
    echo json_encode(['error' => 'Current password is incorrect']);
    exit;
}

$new_hash = password_hash($new, PASSWORD_DEFAULT);
$update = $mysqli->prepare('UPDATE users SET password = ? WHERE id = ?');
$update->bind_param('si', $new_hash, $user_id);

if ($update->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update password']);
}
?>
