@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --surface:#fff; --bg:#f6f7fb; --text:#0f172a; --muted:#64748b; --line:#eaeef3;
    --radius:14px; --shadow:0 10px 30px rgba(2,6,23,.06);
  }
  *{font-family:"DM Sans",system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
  body{background:var(--bg)}
  .card{border:0;border-radius:var(--radius);box-shadow:var(--shadow)}
  .card-header{background:#fff;border-bottom:1px solid var(--line);padding:14px 16px}
  .card-body{padding:24px}

  .top-band{
    background: radial-gradient(1200px 220px at 50% -140px, rgba(59,130,246,.18) 0%, rgba(59,130,246,0) 60%),
                linear-gradient(180deg,#f6f7fb 0%,#f6f7fb 60%,transparent 100%);
    border-bottom:1px solid var(--line);
  }
  .crumb{ display:flex; align-items:center; gap:.5rem; font-size:.95rem; color:#64748b; }
  .crumb a{ color:#0f172a; text-decoration:none; }
  .crumb i{ opacity:.6; }

  .files-toolbar{display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:space-between}
  .folder{font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px}
  .count{display:inline-flex;min-width:26px;height:26px;padding:0 8px;border-radius:999px;background:#f0f2f7;color:#111;align-items:center;justify-content:center;font-size:.85rem;font-weight:600}
  .table td,.table th{vertical-align:middle}
  .table th:first-child,.table td:first-child{width:110px;}
  thead th{color:#475569;font-weight:600;border-bottom:1px solid var(--line);background:#fff}
  tbody td{border-color:var(--line)}
  .status-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.3rem .65rem;border-radius:999px;font-weight:600;font-size:.8rem}
  .status-live{background:rgba(34,197,94,.12);color:#15803d}
  .status-draft{background:rgba(148,163,184,.16);color:#475569}
  .actions{display:flex;gap:.5rem}
  .post-thumbnail{width:72px;height:72px;border-radius:14px;object-fit:cover;border:1px solid var(--line);background:#f8fafc;}
  .post-thumbnail--empty{width:72px;height:72px;border-radius:14px;border:1px dashed var(--line);display:flex;align-items:center;justify-content:center;font-size:.75rem;color:#94a3b8;background:linear-gradient(135deg, rgba(148,163,184,.08), rgba(148,163,184,.02));font-weight:600;letter-spacing:.04em;text-transform:uppercase;}

  .alert-status{
    border-radius:12px;
    border:1px solid rgba(34,197,94,.2);
    background:rgba(34,197,94,.08);
    color:#166534;
    padding:12px 16px;
    font-weight:500;
  }
  .alert-error{
    border-radius:12px;
    border:1px solid rgba(220,38,38,.22);
    background:rgba(248,113,113,.12);
    color:#b91c1c;
    padding:12px 16px;
    font-weight:500;
  }

  .form-label{font-weight:600;color:#0f172a}
  .form-text{color:var(--muted)}
  .form-control,
  textarea.form-control{
    border:1px solid var(--line);
    border-radius:12px;
    padding:12px 14px;
  }
  .featured-image-field .form-control{padding:10px 14px;}
  .featured-image-preview{
    border:1px dashed var(--line);
    border-radius:16px;
    background:#f8fafc;
    padding:12px;
    display:inline-flex;
    max-width:320px;
    max-height:220px;
    align-items:center;
    justify-content:center;
    overflow:hidden;
  }
  .featured-image-preview__image{
    width:100%;
    height:100%;
    object-fit:cover;
    border-radius:12px;
  }
  .form-control:focus,
  textarea.form-control:focus{
    border-color:#cfd2d8;
    box-shadow:0 0 0 .2rem rgba(15,23,42,.08);
  }
  textarea.form-control{min-height:140px}
  .form-switch .form-check-input{width:3em;height:1.5em}
  .form-switch .form-check-input:focus{box-shadow:0 0 0 .2rem rgba(15,23,42,.08)}
  .form-switch .form-check-input:checked{background-color:#111;border-color:#111}
  .invalid-feedback{display:block;font-size:.85rem;color:#dc2626}

  .pagination-wrap{display:flex;flex-direction:column;align-items:center;gap:8px}
  .pager-summary{color:#64748b;font-size:.9rem}
  .pagination-modern{gap:8px}
  .pagination-modern .page-link{
    border:1px solid var(--line);
    background:#fff;
    color:#111;
    border-radius:12px;
    min-width:42px;height:42px;
    padding:0 12px;
    display:flex;align-items:center;justify-content:center;
    font-weight:700;
    box-shadow:0 2px 6px rgba(0,0,0,.04);
  }
  .pagination-modern .page-item.active .page-link{background:#111;border-color:#111;color:#fff}
  .pagination-modern .page-item:not(.active):not(.disabled) .page-link:hover{background:#f2f4f7}
  .pagination-modern .page-item.disabled .page-link{opacity:.45;cursor:not-allowed}
</style>
@endpush
