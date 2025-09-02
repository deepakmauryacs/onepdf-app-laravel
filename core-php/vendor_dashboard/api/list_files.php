<?php
require_once __DIR__ . '/../../config.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['files' => []]);
    exit;
}

$stmt = $mysqli->prepare("SELECT d.id, d.filename, d.size, d.uploaded_at AS updated_at, l.slug FROM documents d LEFT JOIN links l ON l.document_id = d.id WHERE d.user_id = ? ORDER BY d.uploaded_at DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$files = [];
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
// Ensure links work whether the project lives at the web root or in a
// subdirectory by prepending the detected base path.
$basePath = rtrim(dirname(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? ''))), '/');
while ($row = $result->fetch_assoc()) {
    if (!empty($row['slug'])) {
        $row['url'] = $scheme . $host . $basePath . '/view?doc=' . urlencode($row['slug']);
    }
    unset($row['slug']);
    // The filepath is internal and should not be exposed to the client
    unset($row['filepath']);
    $files[] = $row;
}

echo json_encode(['files' => $files]);

