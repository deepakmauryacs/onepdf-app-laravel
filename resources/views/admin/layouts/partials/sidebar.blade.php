<aside class="sidebar" id="sidebar">
  <a href="#" class="brand">
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
    <div class="side-title">GENERAL</div>
    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
    </a>
    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
      <i class="bi bi-people"></i> <span>Users</span>
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
