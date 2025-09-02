<aside class="sidebar" id="sidebar">
  <a href="#" class="brand">
    <span class="logo"><i class="bi bi-grid-1x2"></i></span>
    <span>ONELINKPDF</span>
  </a>

  <!-- Close button for sidebar (visible on mobile) -->
  <button class="sidebar-close" id="sidebarClose">
    <i class="bi bi-x-lg"></i>
  </button>

  <!-- NEW MENU -->
  <nav class="nav flex-column gap-1" id="mainNav">
    <a class="nav-link " href="{{ route('dashboard') }}">
      <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>
    <a class="nav-link " href="">
      <i class="bi bi-cloud-arrow-up"></i> <span>Upload Files</span>
    </a>
    <a class="nav-link " href="">
      <i class="bi bi-folder2-open"></i> <span>Manage Files</span>
    </a>
    <a class="nav-link " href="">
      <i class="bi bi-bar-chart"></i> <span>Analytics</span>
    </a>
    <a class="nav-link " href="">
      <i class="bi bi-gem"></i> <span>Plan</span>
    </a>

    <!-- Settings collapsible -->
    <a class="nav-link " data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
      <i class="bi bi-gear"></i> <span>Settings</span>
      <span class="ms-auto"><i class="bi bi-caret-down-fill"></i></span>
    </a>
    <div class="collapse subnav " id="settingsMenu" data-bs-parent="#mainNav">
      <a class="nav-link " href="{{ route('profile') }}">
        <i class="bi bi-person me-1"></i> <span>Profile</span>
      </a>
      <a class="nav-link " href="">
        <i class="bi bi-key me-1"></i> <span>Change Password</span>
      </a>
    </div>

    <a class="nav-link " href="">
      <i class="bi bi-life-preserver"></i> <span>Help</span>
    </a>
  </nav>

  <div class="bottom">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="logout">
        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
      </button>
    </form>
  </div>
</aside>