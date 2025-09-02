<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $email = trim($_POST['email'] ?? '');

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $mysqli->prepare('INSERT INTO newsletter_subscribers (email) VALUES (?)');
        if ($stmt) {
            $stmt->bind_param('s', $email);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to subscribe']);
            }
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid email']);
    }
    exit;
}

header('Location: /');
exit;

