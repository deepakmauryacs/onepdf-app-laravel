<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$first_name = trim($_POST['first_name'] ?? '');
$last_name  = trim($_POST['last_name'] ?? '');
$company    = trim($_POST['company'] ?? '');
$country    = trim($_POST['country'] ?? '');
$email      = trim($_POST['email'] ?? '');

$errors = [];
if ($first_name === '') $errors[] = 'First name is required';
if ($last_name === '')  $errors[] = 'Last name is required';
if ($company === '')    $errors[] = 'Company is required';
if ($country === '')    $errors[] = 'Country is required';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';

if ($errors) {
    echo json_encode(['error' => implode(', ', $errors)]);
    exit;
}

$stmt = $mysqli->prepare('UPDATE users SET first_name = ?, last_name = ?, company = ?, country = ?, email = ? WHERE id = ?');
$stmt->bind_param('sssssi', $first_name, $last_name, $company, $country, $email, $user_id);

if ($stmt->execute()) {
    $_SESSION['user_name'] = $first_name;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update user']);
}
?>
