@push('styles')
<style>
  .blog-hero{
    padding:72px 0 48px;
    background:
      radial-gradient(900px 420px at 85% -120px, rgba(0,0,0,.05) 0%, rgba(0,0,0,0) 60%),
      radial-gradient(700px 380px at -10% 110%, rgba(0,0,0,.05) 0%, rgba(0,0,0,0) 60%),
      linear-gradient(180deg, #fbfbfc 0%, #f8f9fb 48%, #ffffff 100%);
  }
  .blog-hero .lead{color:var(--muted,#6b7280);max-width:680px;}
  .blog-hero .badge{background:#111;color:#fff;border-radius:999px;padding:.5rem 1.1rem;font-weight:600;letter-spacing:.02em;}
  .blog-hero h1{color:var(--ink,#0b1120);}

  .blog-section{padding:64px 0;background:#ffffff;}
  .blog-card{
    background:var(--panel,#ffffff);
    border:1px solid var(--line,#e6e7eb);
    border-radius:18px;
    padding:24px;
    height:100%;
    display:flex;
    flex-direction:column;
    position:relative;
    box-shadow:0 12px 32px rgba(15,23,42,.08);
    transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }
  .blog-card:hover{transform:translateY(-6px);box-shadow:0 18px 45px rgba(15,23,42,.12);border-color:#d8dae2;}
  .blog-card__meta{color:var(--muted,#6b7280);font-size:.9rem;margin-bottom:12px;display:flex;align-items:center;gap:.5rem;}
  .blog-card__title{font-size:1.3rem;margin-bottom:.75rem;font-weight:700;color:var(--ink,#0b1120);}
  .blog-card__title a{color:inherit;text-decoration:none;}
  .blog-card__title a:hover{text-decoration:underline;}
  .blog-card__excerpt{color:var(--muted,#6b7280);margin-bottom:1.4rem;line-height:1.6;}
  .blog-card__footer{margin-top:auto;font-weight:600;display:inline-flex;align-items:center;gap:.4rem;color:#0f172a;}
  .blog-card__footer .bi{transition:transform .2s ease;}
  .blog-card:hover .blog-card__footer .bi{transform:translateX(4px);}

  .blog-pagination{display:flex;flex-direction:column;align-items:center;gap:8px;margin-top:48px;}
  .blog-pagination .pager-summary{color:var(--muted,#6b7280);font-size:.9rem;}
  .blog-pagination .pagination{gap:10px;}
  .blog-pagination .page-link{border-radius:12px;border:1px solid var(--line,#e6e7eb);color:#111;padding:.55rem 1rem;box-shadow:0 2px 6px rgba(15,23,42,.06);font-weight:600;}
  .blog-pagination .page-item.active .page-link{background:#111;border-color:#111;color:#fff;}
  .blog-pagination .page-link:hover{background:#f4f5f8;}

  .blog-empty{padding:80px 0;text-align:center;color:var(--muted,#6b7280);font-size:1.05rem;}

  .blog-breadcrumb{color:var(--muted,#6b7280);font-size:.95rem;display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;}
  .blog-breadcrumb a{color:#0f172a;text-decoration:none;font-weight:500;}
  .blog-breadcrumb a:hover{text-decoration:underline;}

  .blog-meta{color:var(--muted,#6b7280);font-size:.95rem;display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;}
  .blog-meta .dot{width:6px;height:6px;border-radius:999px;background:var(--muted,#6b7280);display:inline-block;}

  .blog-content{background:var(--panel,#ffffff);border-radius:20px;border:1px solid var(--line,#e6e7eb);padding:32px;box-shadow:0 20px 45px rgba(15,23,42,.08);}
  .blog-content p{font-size:1.08rem;line-height:1.75;color:var(--ink,#111827);margin-bottom:1.2rem;}
  .blog-content h2,.blog-content h3,.blog-content h4{color:#0f172a;font-weight:700;margin-top:2rem;margin-bottom:1rem;}
  .blog-content ul,.blog-content ol{margin-bottom:1.2rem;padding-left:1.3rem;}
  .blog-content li{margin-bottom:.5rem;}
  .blog-content a{color:#111;text-decoration:underline;}
  .blog-content blockquote{border-left:4px solid #111;padding-left:1rem;color:#0f172a;font-style:italic;margin:1.5rem 0;}
  .blog-back{display:inline-flex;align-items:center;gap:.45rem;margin-top:24px;font-weight:600;color:#0f172a;text-decoration:none;}
  .blog-back:hover{text-decoration:underline;}

  .blog-related h5{font-weight:700;margin-bottom:1rem;color:#0f172a;}
  .blog-related .blog-card{box-shadow:0 10px 28px rgba(15,23,42,.07);padding:20px;}
  .blog-related .blog-card__title{font-size:1.05rem;margin-bottom:.5rem;}

  @media (max-width: 768px){
    .blog-hero{padding:56px 0 32px;text-align:center;}
    .blog-content{padding:24px;}
  }
</style>
@endpush
