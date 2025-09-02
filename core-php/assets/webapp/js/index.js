document.addEventListener('DOMContentLoaded', () => {
  // Copy hero snippet
  const copySnippetBtn = document.getElementById('copySnippet');
  if (copySnippetBtn) {
    copySnippetBtn.addEventListener('click', async () => {
      const code = document.getElementById('snippet').innerText;
      try { await navigator.clipboard.writeText(code); } catch (e) {}
      const original = copySnippetBtn.innerHTML;
      copySnippetBtn.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Copied';
      setTimeout(() => (copySnippetBtn.innerHTML = original), 1600);
    });
  }

  // Copy token in demo section
  const copyTokenBtn = document.getElementById('copyToken');
  if (copyTokenBtn) {
    copyTokenBtn.addEventListener('click', async () => {
      try { await navigator.clipboard.writeText('DEMO_TOKEN'); } catch (e) {}
      const original = copyTokenBtn.innerHTML;
      copyTokenBtn.innerHTML = '<i class="bi bi-check2-circle"></i>';
      setTimeout(() => (copyTokenBtn.innerHTML = original), 1600);
    });
  }

  // Year in footer
  const yearEl = document.getElementById('y');
  if (yearEl) {
    yearEl.textContent = new Date().getFullYear();
  }

  // Navbar scroll effect
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        navbar.classList.add('shadow');
      } else {
        navbar.classList.remove('shadow');
      }
    });
  }
});

