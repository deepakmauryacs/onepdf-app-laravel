<?php
require_once '../config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}

define('INCLUDE_PATH', __DIR__ . '/includes/');
include(INCLUDE_PATH . 'header.php');   // must include Bootstrap Icons in header
include(INCLUDE_PATH . 'sidebar.php');
include(INCLUDE_PATH . 'topbar.php');
?>

<!-- Page-only CSS for inline eye icons -->
<style>
  .password-wrapper{ position: relative; }
  .password-wrapper input.form-control{ padding-right: 2.5rem; } /* room for eye */
  .password-wrapper .toggle-pass{
    position: absolute; top: 50%; right: 10px; transform: translateY(-50%);
    cursor: pointer; font-size: 1.1rem; color: #6b7280;
  }
  .password-wrapper .toggle-pass:hover{ color: #374151; }
</style>

<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="bi bi-key mr-2"></i> Change Password</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
      <i class="bi bi-gear mr-1"></i> Change Settings
    </a>
  </div>

  <div class="row">
    <div class="col-lg-6 offset-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex align-items-center">
          <i class="bi bi-shield-lock mr-2"></i>
          <span class="font-weight-bold">Update Password</span>
        </div>
        <div class="card-body">
          <!-- Disable native validation; we use JS -->
          <form id="passwordForm" novalidate>
            <!-- Current Password -->
            <div class="form-group mb-3">
              <label for="currentPassword" class="form-label">
                <i class="bi bi-lock mr-1"></i> Current Password
              </label>
              <div class="password-wrapper">
                <input type="password" name="current_password" id="currentPassword" class="form-control">
                <i class="bi bi-eye toggle-pass" data-target="#currentPassword" aria-label="Show password"></i>
              </div>
              <div class="invalid-feedback d-block" id="err-current" style="display:none;"></div>
            </div>

            <!-- New Password -->
            <div class="form-group mb-3">
              <label for="newPassword" class="form-label">
                <i class="bi bi-shield-lock mr-1"></i> New Password
              </label>
              <div class="password-wrapper">
                <input type="password" name="new_password" id="newPassword" class="form-control">
                <i class="bi bi-eye toggle-pass" data-target="#newPassword" aria-label="Show password"></i>
              </div>
              <small class="text-muted">Min 8 chars, include a letter & a number.</small>
              <div class="invalid-feedback d-block" id="err-new" style="display:none;"></div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-3">
              <label for="confirmPassword" class="form-label">
                <i class="bi bi-shield-check mr-1"></i> Confirm Password
              </label>
              <div class="password-wrapper">
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control">
                <i class="bi bi-eye toggle-pass" data-target="#confirmPassword" aria-label="Show password"></i>
              </div>
              <div class="invalid-feedback d-block" id="err-confirm" style="display:none;"></div>
            </div>

            <div class="d-flex justify-content-end" style="gap:10px;">
              <button type="reset" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset
              </button>
              <button type="submit" id="btnChange" class="btn btn-primary">
                <i class="bi bi-save mr-1"></i> Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS: eye toggles + custom validation + submit -->
<script>
// Eye icon toggles (no buttons)
document.querySelectorAll('.toggle-pass').forEach(function(icon){
  icon.addEventListener('click', function(){
    const input = document.querySelector(this.dataset.target);
    if(!input) return;
    if(input.type === 'password'){
      input.type = 'text';
      this.classList.remove('bi-eye'); this.classList.add('bi-eye-slash');
      this.setAttribute('aria-label','Hide password');
    } else {
      input.type = 'password';
      this.classList.remove('bi-eye-slash'); this.classList.add('bi-eye');
      this.setAttribute('aria-label','Show password');
    }
  });
});

// JS-only validation
(function(){
  const form = document.getElementById('passwordForm');
  const btn  = document.getElementById('btnChange');

  function setError(el, msgId, msg){
    el.classList.add('is-invalid');
    const m = document.getElementById(msgId);
    if(m){ m.style.display = msg ? 'block' : 'none'; m.textContent = msg || ''; }
  }
  function clearError(el, msgId){
    el.classList.remove('is-invalid');
    const m = document.getElementById(msgId);
    if(m){ m.style.display = 'none'; m.textContent = ''; }
  }

  function validate(){
    let ok = true;
    const cur = document.getElementById('currentPassword');
    const nw  = document.getElementById('newPassword');
    const con = document.getElementById('confirmPassword');

    // reset
    clearError(cur,'err-current'); clearError(nw,'err-new'); clearError(con,'err-confirm');

    if(!cur.value.trim()){ setError(cur,'err-current','Current password is required.'); ok = false; }

    const pwd = nw.value;
    const hasLetter = /[A-Za-z]/.test(pwd);
    const hasNumber = /\d/.test(pwd);
    if(pwd.length < 8 || !hasLetter || !hasNumber){
      setError(nw,'err-new','Password must be at least 8 characters and include a letter and a number.');
      ok = false;
    }

    if(con.value !== pwd){
      setError(con,'err-confirm','Passwords do not match.');
      ok = false;
    }

    return ok;
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    if(!validate()) return;

    btn.disabled = true;
    const original = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Saving...';

    try{
      const formData = new FormData(form);
      const res = await fetch('api/_change_password.php', { method:'POST', body: formData });
      const result = await res.json();
      if(result.success){
        alert('Password updated successfully');
        form.reset();
        // reset eye icons back to hidden state
        document.querySelectorAll('.toggle-pass').forEach(i=>{
          const target = document.querySelector(i.dataset.target);
          if(target){ target.type = 'password'; }
          i.classList.remove('bi-eye-slash'); i.classList.add('bi-eye');
        });
      }else{
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

<?php include(INCLUDE_PATH . 'footer.php'); ?>
