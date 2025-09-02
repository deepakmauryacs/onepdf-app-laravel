// Sidebar toggle functionality
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const sidebarClose = document.getElementById('sidebarClose');
const mainContent = document.getElementById('mainContent');

function toggleDesktopSidebar() {
  if (window.innerWidth >= 992) {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('collapsed');
  }
}

function toggleMobileSidebar() {
  if (window.innerWidth < 992) {
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
  }
}

function toggleSidebar() {
  if (window.innerWidth >= 992) {
    toggleDesktopSidebar();
  } else {
    toggleMobileSidebar();
  }
}

toggleBtn?.addEventListener('click', toggleSidebar);

overlay?.addEventListener('click', () => {
  sidebar.classList.remove('show');
  overlay.classList.remove('show');
  document.body.style.overflow = '';
});

sidebarClose?.addEventListener('click', () => {
  sidebar.classList.remove('show');
  overlay.classList.remove('show');
  document.body.style.overflow = '';
});

// Handle window resize
window.addEventListener('resize', function() {
  if (window.innerWidth >= 992) {
    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
  } else if (window.innerWidth < 992 && !sidebar.classList.contains('collapsed')) {
    sidebar.classList.remove('collapsed');
  }
});

// Mobile dropdown fixes (notif/user)
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.dropdown-toggle').forEach(function(dropdownToggle) {
    dropdownToggle.addEventListener('click', function(e) {
      if (window.innerWidth < 992) {
        const dropdownMenu = this.nextElementSibling;
        if (dropdownMenu?.classList.contains('show')) return;
        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
          menu.classList.remove('show');
        });
        dropdownMenu?.classList.add('show');
        e.stopPropagation();
        e.preventDefault();
      }
    });
  });

  document.addEventListener('click', function(e) {
    if (window.innerWidth < 992) {
      if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
          menu.classList.remove('show');
        });
      }
    }
  });
});