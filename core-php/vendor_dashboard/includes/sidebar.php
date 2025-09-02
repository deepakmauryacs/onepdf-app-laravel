<?php
$current = basename($_SERVER['PHP_SELF']);
$menu = [
    ['title' => 'Dashboard', 'icon' => 'bi-speedometer2', 'url' => 'dashboard'],
    ['divider' => true],
    [
        'title' => 'File',
        'icon'  => 'bi-folder',
        'children' => [
            ['title' => 'Upload File', 'url' => 'upload'],
            ['title' => 'File List',  'url' => 'file_list'],
        ]
    ],
    ['title' => 'Analytics', 'icon' => 'bi-graph-up', 'url' => 'analytics'],
    ['title' => 'Plan', 'icon' => 'bi-gem', 'url' => 'plan'],
    [
        'title' => 'Setting',
        'icon'  => 'bi-gear',
        'children' => [
            ['title' => 'Profile Update', 'url' => 'profile'],
            ['title' => 'Change Password', 'url' => 'change_password'],
        ]
    ],
    ['divider' => true],
    ['title' => 'Logout', 'icon' => 'bi-box-arrow-right', 'url' => '../logout'],
];
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="bi bi-file-earmark-pdf"></i>
        </div>
        <div class="sidebar-brand-text mx-3">PDFOneLink</div>
    </a>
    <?php foreach ($menu as $item): ?>
        <?php if (isset($item['divider'])): ?>
            <hr class="sidebar-divider my-0">
            <?php continue; ?>
        <?php endif; ?>
        <?php if (isset($item['children'])): ?>
            <?php
            $collapse_id = strtolower(str_replace(' ', '', $item['title'])) . 'Menu';
            $active = in_array($current, array_column($item['children'], 'url'));
            ?>
            <li class="nav-item<?php echo $active ? ' active' : ''; ?>">
                <a class="nav-link<?php echo $active ? '' : ' collapsed'; ?>" href="#" data-toggle="collapse" data-target="#<?php echo $collapse_id; ?>" aria-expanded="<?php echo $active ? 'true' : 'false'; ?>" aria-controls="<?php echo $collapse_id; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                    <span><?php echo $item['title']; ?></span>
                </a>
                <div id="<?php echo $collapse_id; ?>" class="collapse<?php echo $active ? ' show' : ''; ?>" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php foreach ($item['children'] as $child): ?>
                            <a class="collapse-item<?php echo $current === $child['url'] ? ' active' : ''; ?>" href="<?php echo $child['url']; ?>"><?php echo $child['title']; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </li>
        <?php else: ?>
            <li class="nav-item<?php echo $current === $item['url'] ? ' active' : ''; ?>">
                <a class="nav-link" href="<?php echo $item['url']; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                    <span><?php echo $item['title']; ?></span>
                </a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
    <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
</ul>
<!-- End of Sidebar -->
