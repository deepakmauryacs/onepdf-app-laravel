<?php
require_once __DIR__ . '/config.php';

// Prevent direct access by ensuring the request originates from our domain
$referrer = $_SERVER['HTTP_REFERER'] ?? '';
$host     = $_SERVER['HTTP_HOST'] ?? '';
if (!$referrer || parse_url($referrer, PHP_URL_HOST) !== $host) {
    http_response_code(403);
    echo 'Access is denied';
    exit;
}

$slug = $_GET['code'] ?? '';
if (!$slug) {
    http_response_code(400);
    echo 'Missing link code';
    exit;
}

// Lookup the document path and permissions using the slug
$stmt = $mysqli->prepare("SELECT l.permissions, d.filepath FROM links l JOIN documents d ON l.document_id = d.id WHERE l.slug = ?");
$stmt->bind_param('s', $slug);
$stmt->execute();
$stmt->bind_result($permJson, $filepath);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo 'Invalid link';
    exit;
}
$stmt->close();

$perms = json_decode($permJson, true) ?? [];

// If a download is requested, ensure the permission is granted
$download = isset($_GET['download']);
if ($download && empty($perms['download'])) {
    http_response_code(403);
    echo 'Download not permitted';
    exit;
}

if (!is_file($filepath)) {
    http_response_code(404);
    echo 'File not found';
    exit;
}

$filename = basename($filepath);
header('Content-Type: application/pdf');
header('Content-Length: ' . filesize($filepath));
if ($download) {
    header('Content-Disposition: attachment; filename="' . $filename . '"');
} else {
    header('Content-Disposition: inline; filename="' . $filename . '"');
}

// Stream the file contents
readfile($filepath);
