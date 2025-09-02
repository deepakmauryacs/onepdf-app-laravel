<?php
require_once __DIR__ . '/../../config.php';

// Ensure the user is logged in
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Support uploads sent as either `file` or `pdf`
$key = isset($_FILES['file']) ? 'file' : (isset($_FILES['pdf']) ? 'pdf' : null);
if ($key === null) {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded']);
    exit;
}

$file = $_FILES[$key];
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'Upload failed']);
    exit;
}

// Validate file type (PDF only)
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (strpos($mime, 'application/pdf') !== 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Only PDF files are allowed']);
    exit;
}

// Validate file size (<= 50MB)
$maxSize = 50 * 1024 * 1024; // 50MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File exceeds 50MB limit']);
    exit;
}

// Determine user's folder based on use_id
$useId = $_SESSION['use_id'] ?? null;
if (!$useId) {
    $stmt = $mysqli->prepare('SELECT use_id FROM users WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($useId);
    $stmt->fetch();
    $stmt->close();
    if (!$useId) {
        http_response_code(500);
        echo json_encode(['error' => 'User folder not found']);
        exit;
    }
    $_SESSION['use_id'] = $useId;
}

$uploadDir = dirname(__DIR__, 2) . '/uploads/' . $useId . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Sanitize original filename for display/storage
$originalName  = $file['name'];
$sanitizedName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
$extension     = strtolower(pathinfo($sanitizedName, PATHINFO_EXTENSION));

// Generate random stored filename to avoid collisions and execution of user-supplied names
do {
    $storedName = bin2hex(random_bytes(8));
    if ($extension) {
        $storedName .= '.' . $extension;
    }
    $target = $uploadDir . $storedName;
} while (file_exists($target));

if (!move_uploaded_file($file['tmp_name'], $target)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file']);
    exit;
}
chmod($target, 0644);

// Record document in database
$relative = 'uploads/' . $useId . '/' . $storedName;
$stmt = $mysqli->prepare("INSERT INTO documents (user_id, filename, filepath, size) VALUES (?,?,?,?)");
$stmt->bind_param('issi', $userId, $sanitizedName, $relative, $file['size']);
$stmt->execute();
$docId = $stmt->insert_id;
$stmt->close();

echo json_encode(['success' => true, 'id' => $docId]);
