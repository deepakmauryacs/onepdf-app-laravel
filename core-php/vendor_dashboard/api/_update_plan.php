<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$plan_id = intval($_POST['plan_id'] ?? 0);

if (!$plan_id) {
    echo json_encode(['error' => 'Invalid plan']);
    exit;
}

// Fetch plan info
$stmt = $mysqli->prepare('SELECT billing_cycle FROM plans WHERE id = ?');
$stmt->bind_param('i', $plan_id);
$stmt->execute();
$plan = $stmt->get_result()->fetch_assoc();
if (!$plan) {
    echo json_encode(['error' => 'Plan not found']);
    exit;
}

$billing = $plan['billing_cycle'];
$start_date = date('Y-m-d');
$end_date = null;
if ($billing === 'month') {
    $end_date = date('Y-m-d', strtotime('+1 month'));
} elseif ($billing === 'year') {
    $end_date = date('Y-m-d', strtotime('+1 year'));
}

$mysqli->begin_transaction();
try {
    // Mark existing plans inactive
    $stmt = $mysqli->prepare('UPDATE user_plan SET status = 2 WHERE user_id = ? AND status = 1');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    // Insert new plan
    $stmt = $mysqli->prepare('INSERT INTO user_plan (user_id, plan_id, start_date, end_date, status) VALUES (?, ?, ?, ?, 1)');
    $stmt->bind_param('iiss', $user_id, $plan_id, $start_date, $end_date);
    $stmt->execute();

    $mysqli->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $mysqli->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update plan']);
}
?>
