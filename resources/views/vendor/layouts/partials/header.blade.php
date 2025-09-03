<!-- Topbar -->
<div class="topbar">
  <button class="btn icon-btn" id="toggleSidebar" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>

  <!-- Search bar - visible on desktop, hidden on mobile -->
  <div class="search">
    <i class="bi bi-search"></i>
    <input class="form-control" placeholder="Search" />
  </div>

  <div class="topbar-icons">
    <button class="icon-btn"><i class="bi bi-gear"></i></button>

    <!-- Notification Dropdown -->
    <div class="dropdown">
      <button class="icon-btn dropdown-toggle position-relative" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell"></i>
        @if($unreadCount > 0)
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadCount }}</span>
        @endif
      </button>
      <div class="dropdown-menu dropdown-menu-end notif-menu shadow-sm mt-2">
        <div class="notif-head">
          <span>Notifications</span><a href="{{ route('vendor.notifications.index') }}" class="notif-clear">View All</a>
        </div>
        <div class="notif-list">
          @forelse($notifications as $note)
            <div class="notif-item">
              <div class="notif-avatar"><i class="bi bi-info-circle"></i></div>
              <div class="notif-body">
                <div class="notif-name">{{ $note->title }}</div>
                <p class="notif-text mb-0">{{ $note->message }}</p>
              </div>
            </div>
          @empty
            <div class="p-3 text-center text-muted">No notifications</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- User dropdown -->
    <div class="dropdown">
      <button class="avatar-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
        <i class="bi bi-person-circle"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end user-menu shadow-sm mt-2">
        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
        <li><a class="dropdown-item d-flex align-items-center" href="{{ route('vendor.password.edit') }}"><i class="bi bi-key me-2"></i> Change Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item d-flex align-items-center">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</div>
