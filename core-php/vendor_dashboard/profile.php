<?php
require_once '../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare('SELECT first_name, last_name, company, country, email FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

define('INCLUDE_PATH', __DIR__ . '/includes/');

include(INCLUDE_PATH . 'header.php');   // <head> + CSS (sb-admin-2.min.css, bootstrap-icons CDN, custom.css etc.)
include(INCLUDE_PATH . 'sidebar.php');  // left sidebar
include(INCLUDE_PATH . 'topbar.php');   // top navbar
?>

<!-- Local radius fix just for this page (you can move to custom.css if you want) -->
<style>
  /* Base rounded size to match your boxy look */
  :root { --ig-radius: .5rem; }

  /* Left icon (prepend) layout */
  .ig-left > .input-group-prepend .input-group-text{
    border-top-left-radius: var(--ig-radius) !important;
    border-bottom-left-radius: var(--ig-radius) !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .ig-left > .form-control{
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
    border-top-right-radius: var(--ig-radius) !important;
    border-bottom-right-radius: var(--ig-radius) !important;
  }

  /* Right icon (append) layout */
  .ig-right > .form-control{
    border-top-left-radius: var(--ig-radius) !important;
    border-bottom-left-radius: var(--ig-radius) !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .ig-right > .input-group-append .input-group-text{
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
    border-top-right-radius: var(--ig-radius) !important;
    border-bottom-right-radius: var(--ig-radius) !important;
  }

  /* Make sure borders join cleanly */
  .input-group .input-group-text { border-color: #e5e7eb; }
  .input-group .form-control    { border-color: #e5e7eb; }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Title -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <i class="bi bi-person-circle mr-2"></i> Profile
    </h1>
  </div>

  <div class="row">

    <!-- Right Summary Card -->
    <div class="col-lg-4">
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center">
          <i class="bi bi-person-lines-fill mr-2"></i>
          <span class="font-weight-bold">Your Info</span>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                 style="width:48px;height:48px;">
              <i class="bi bi-person text-white"></i>
            </div>
            <div class="ml-3">
              <div class="font-weight-bold">
                <?php echo htmlspecialchars(($user['first_name'] ?? '').' '.($user['last_name'] ?? '')); ?>
              </div>
              <div class="text-muted small"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
            </div>
          </div>
          <div class="mb-2"><i class="bi bi-building mr-2 text-secondary"></i> <?php echo htmlspecialchars($user['company'] ?? '—'); ?></div>
          <div><i class="bi bi-geo-alt mr-2 text-secondary"></i> <?php echo htmlspecialchars($user['country'] ?? '—'); ?></div>
        </div>
      </div>
    </div>
    
    <!-- Profile Form -->
    <div class="col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center">
          <i class="bi bi-pencil-square mr-2"></i>
          <span class="font-weight-bold">Edit Profile</span>
        </div>
        <div class="card-body">
          <form id="profileForm" novalidate>
            <div class="form-row">
              <!-- First Name (icon LEFT) -->
              <div class="form-group col-md-6">
                <label for="firstName" class="form-label">
                  <i class="bi bi-person mr-1"></i> First Name
                </label>
                <div class="input-group ig-left">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                  </div>
                  <input type="text" name="first_name" id="firstName" class="form-control"
                         value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
                </div>
                <small class="text-muted">Enter your given name.</small>
              </div>

              <!-- Last Name (icon RIGHT) -->
              <div class="form-group col-md-6">
                <label for="lastName" class="form-label">
                  <i class="bi bi-person-badge mr-1"></i> Last Name
                </label>
                <div class="input-group ig-right">
                  <input type="text" name="last_name" id="lastName" class="form-control"
                         value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
                  <div class="input-group-append">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Company (icon LEFT) -->
            <div class="form-group">
              <label for="company" class="form-label">
                <i class="bi bi-building mr-1"></i> Company
              </label>
              <div class="input-group ig-left">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="bi bi-building"></i></span>
                </div>
                <input type="text" name="company" id="company" class="form-control"
                       value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>" required>
              </div>
            </div>

            <!-- Country (icon LEFT) -->
            <div class="form-group">
              <label for="country" class="form-label">
                <i class="bi bi-geo-alt mr-1"></i> Country
              </label>
              <div class="input-group ig-left">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                </div>
                <input type="text" name="country" id="country" class="form-control"
                       value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>" required>
              </div>
            </div>

            <!-- Email (icon LEFT) -->
            <div class="form-group">
              <label for="email" class="form-label">
                <i class="bi bi-envelope mr-1"></i> Email
              </label>
              <div class="input-group ig-left">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                </div>
                <input type="email" name="email" id="email" class="form-control"
                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
              </div>
            </div>

            <button type="submit" id="btnSave" class="btn btn-primary">
              <i class="bi bi-save mr-1"></i> Save
            </button>
            <button type="reset" class="btn btn-outline-secondary ml-2">
              <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
            </button>
          </form>
        </div>
      </div>
    </div>

    
  </div>

</div>
<!-- /.container-fluid -->

<!-- Page-level JS -->
<script>
(function(){
  const form = document.getElementById('profileForm');
  const btn  = document.getElementById('btnSave');

  form.addEventListener('submit', async function(e){
    e.preventDefault();

    if (!form.checkValidity()) {
      alert('Please fill all required fields.');
      return;
    }

    btn.disabled = true;
    const original = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Saving...';

    try{
      const formData = new FormData(form);
      const res = await fetch('api/_update_user.php', { method:'POST', body: formData });
      const result = await res.json();
      if(result.success){
        alert('Profile updated successfully');
      } else {
        alert(result.error || 'Update failed');
      }
    }catch(err){
      alert('Network error. Please try again.');
    }finally{
      btn.disabled = false;
      btn.innerHTML = original;
    }
  });
})();
</script>

<?php
include(INCLUDE_PATH . 'footer.php');
?>
