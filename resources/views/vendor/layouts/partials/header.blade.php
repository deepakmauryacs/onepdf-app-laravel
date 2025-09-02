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
      <button class="icon-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell"></i>
      </button>
      <div class="dropdown-menu dropdown-menu-end notif-menu shadow-sm mt-2">
        <div class="notif-head">
          <span>Notifications</span><a href="#" class="notif-clear">Clear All</a>
        </div>
        <div class="notif-list">
          <div class="notif-item">
            <div class="notif-avatar"><i class="bi bi-person"></i></div>
            <div class="notif-body">
              <div class="notif-name">Josephine Thompson</div>
              <p class="notif-text mb-0">commented on admin panel "Wow 🤩! this admin looks good and awesome design"</p>
            </div>
          </div>
          <div class="notif-item">
            <div class="notif-avatar">D</div>
            <div class="notif-body">
              <div class="notif-name">Donoghue Susan</div>
              <p class="notif-text mb-0">Hi, how are you? What about our next meeting</p>
            </div>
          </div>
          <div class="notif-item">
            <div class="notif-avatar"><img src="https://i.pravatar.cc/80?img=5" alt=""></div>
            <div class="notif-body">
              <div class="notif-name">Jacob Gines</div>
              <p class="notif-text mb-0">Answered to your comment on the dashboard post</p>
            </div>
          </div>
        </div>
        <div class="notif-cta">
          <button class="btn btn-cta">View All Notification <i class="bi bi-arrow-right-short ms-1"></i></button>
        </div>
      </div>
    </div>

    <!-- User dropdown -->
    <div class="dropdown">
      <button class="avatar-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Account">
        <i class="bi bi-person-circle"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end user-menu shadow-sm mt-2">
        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-key me-2"></i> Change Password</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout </a></li>
      </ul>
    </div>
  </div>
</div>