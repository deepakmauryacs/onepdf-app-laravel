<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}
$userId = $_SESSION['user_id'];

// Mark notifications as read
$stmt = $mysqli->prepare("UPDATE notifications SET is_read=1, read_at=NOW() WHERE (audience='all' OR (audience='user' AND user_id=?)) AND is_read=0");
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->close();

// Fetch notifications
$stmt = $mysqli->prepare("SELECT title, message, type, created_at FROM notifications WHERE (audience='all' OR (audience='user' AND user_id=?)) ORDER BY created_at DESC");
$stmt->bind_param('i', $userId);
$stmt->execute();
$notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Notifications</h1>
    <?php if (empty($notifications)): ?>
        <p>No notifications found.</p>
    <?php else: ?>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach ($notifications as $note): ?>
                    <li class="list-group-item">
                        <div class="small text-gray-500"><?php echo date('F j, Y', strtotime($note['created_at'])); ?></div>
                        <strong><?php echo htmlspecialchars($note['title']); ?></strong>
                        <div><?php echo htmlspecialchars($note['message']); ?></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
