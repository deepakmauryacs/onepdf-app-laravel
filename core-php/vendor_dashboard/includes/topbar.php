<?php
$userId = $_SESSION['user_id'] ?? 0;
$notifications = [];
$unreadCount = 0;

if ($userId) {
    $stmt = $mysqli->prepare("SELECT id, title, message, type, action_url, created_at, is_read FROM notifications WHERE (audience='all' OR (audience='user' AND user_id=?)) ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT COUNT(*) AS cnt FROM notifications WHERE (audience='all' OR (audience='user' AND user_id=?)) AND is_read=0");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $unreadCount = $stmt->get_result()->fetch_assoc()['cnt'] ?? 0;
    $stmt->close();
}

$typeIcons = [
    'info'    => ['bi-info-circle', 'bg-primary'],
    'success' => ['bi-check-circle', 'bg-success'],
    'warning' => ['bi-exclamation-triangle', 'bg-warning'],
    'error'   => ['bi-x-circle', 'bg-danger'],
    'system'  => ['bi-gear', 'bg-secondary']
];
?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
<!-- Main Content -->
<div id="content">
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="bi bi-list"></i>
        </button>

 

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-search"></i>
                </a>
                <!-- Dropdown - Messages -->
                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                    aria-labelledby="searchDropdown">
                    <form class="form-inline mr-auto w-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small"
                                placeholder="Search for..." aria-label="Search"
                                aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <?php if ($unreadCount > 0): ?>
                        <span class="badge badge-danger badge-counter"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
                <!-- Dropdown - Alerts -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Alerts Center
                    </h6>
                    <?php if (empty($notifications)): ?>
                        <span class="dropdown-item text-center small text-gray-500">No notifications</span>
                    <?php else: ?>
                        <?php foreach ($notifications as $note): 
                            $icon = $typeIcons[$note['type']] ?? $typeIcons['info'];
                        ?>
                        <a class="dropdown-item d-flex align-items-center" href="<?php echo htmlspecialchars($note['action_url'] ?? '#'); ?>">
                            <div class="mr-3">
                                <div class="icon-circle <?php echo $icon[1]; ?>">
                                    <i class="bi <?php echo $icon[0]; ?> text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500"><?php echo date('F j, Y', strtotime($note['created_at'])); ?></div>
                                <span class="<?php echo $note['is_read'] ? '' : 'font-weight-bold'; ?>"><?php echo htmlspecialchars($note['title']); ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <a class="dropdown-item text-center small text-gray-500" href="notifications.php">Show All Alerts</a>
                </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
                    <img class="img-profile rounded-circle"
                        src="<?php echo $assets_path; ?>img/undraw_profile.svg">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="profile.php">
                        <i class="bi bi-person-fill me-2 text-gray-400"></i>
                        Profile
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="bi bi-box-arrow-right me-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>  
    <!-- End of Topbar -->
