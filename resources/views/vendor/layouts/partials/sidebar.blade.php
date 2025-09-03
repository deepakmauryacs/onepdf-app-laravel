<aside class="sidebar" id="sidebar">
  <a href="{{ route('dashboard') }}" class="brand">
    <span class="logo"><i class="bi bi-grid-1x2"></i></span>
     <img src="{{ asset('assets/logo/onelinkpdf-logo.png') }}"
         alt="ONELINKPDF logo"
         class="brand-logo" style="height: 35px;">
  </a>
   

  <!-- Close button for sidebar (visible on mobile) -->
  <button class="sidebar-close" id="sidebarClose">
    <i class="bi bi-x-lg"></i>
  </button>

  <!-- NEW MENU -->
  <nav class="nav flex-column gap-1" id="mainNav">
    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
      <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>
    <a class="nav-link {{ request()->routeIs('vendor.files.index') ? 'active' : '' }}" href="{{ route('vendor.files.index') }}">
      <i class="bi bi-cloud-arrow-up"></i> <span>Upload Files</span>
    </a>
    <a class="nav-link {{ request()->routeIs('vendor.files.manage') ? 'active' : '' }}" href="{{ route('vendor.files.manage') }}">
      <i class="bi bi-folder2-open"></i> <span>Manage Files</span>
    </a>
    <a class="nav-link {{ request()->routeIs('vendor.analytics.index') ? 'active' : '' }}" href="{{ route('vendor.analytics.index') }}">
      <i class="bi bi-bar-chart"></i> <span>Analytics</span>
    </a>
    <a class="nav-link {{ request()->routeIs('vendor.plan.index') ? 'active' : '' }}" href="{{ route('vendor.plan.index') }}">
      <i class="bi bi-gem"></i> <span>Plan</span>
    </a>

    <!-- Settings collapsible -->
    <a class="nav-link {{ request()->routeIs('profile') || request()->routeIs('password.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="{{ request()->routeIs('profile') || request()->routeIs('password.*') ? 'true' : 'false' }}" aria-controls="settingsMenu">
      <i class="bi bi-gear"></i> <span>Settings</span>
      <span class="ms-auto"><i class="bi bi-caret-down-fill"></i></span>
    </a>
    <div class="collapse subnav {{ request()->routeIs('profile') || request()->routeIs('password.*') ? 'show' : '' }}" id="settingsMenu" data-bs-parent="#mainNav">
      <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
        <i class="bi bi-person me-1"></i> <span>Profile</span>
      </a>
      <a class="nav-link {{ request()->routeIs('password.edit') ? 'active' : '' }}" href="{{ route('password.edit') }}">
        <i class="bi bi-key me-1"></i> <span>Change Password</span>
      </a>
    </div>

    <a class="nav-link {{ request()->routeIs('vendor.help.manage') ? 'active' : '' }}" href="{{ route('vendor.help.manage') }}">
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
