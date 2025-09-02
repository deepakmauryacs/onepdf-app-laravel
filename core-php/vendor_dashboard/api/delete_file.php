<?php
require_once __DIR__ . '/../../config.php';

$userId = $_SESSION['user_id'] ?? null;
$id = $_POST['id'] ?? null;
if (!$userId || !$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

$stmt = $mysqli->prepare("SELECT filepath FROM documents WHERE id=? AND user_id=?");
$stmt->bind_param('ii', $id, $userId);
$stmt->execute();
$stmt->bind_result($filepath);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit;
}
$stmt->close();

$fullPath = dirname(__DIR__, 2) . '/' . $filepath;
if (file_exists($fullPath)) {
    unlink($fullPath);
}

$del = $mysqli->prepare("DELETE FROM documents WHERE id=? AND user_id=?");
$del->bind_param('ii', $id, $userId);
$del->execute();

echo json_encode(['success' => true]);
