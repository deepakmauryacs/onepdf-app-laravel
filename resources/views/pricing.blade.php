@extends('layouts.app')

@section('title', 'Pricing - PDFOneLink')

@push('styles')
  {{-- Page CSS: Pricing (Black & White) --}}
  <style>
    :root{
      --ink:#0a0a0a;
      --muted:#6b7280;
      --line:#e6e7eb;
      --panel:#ffffff;
      --chip:#f3f4f6;
      --section:#f6f7f8;
      --radius:14px;
      --shadow:0 6px 24px rgba(0,0,0,.06);
      --shadow-lg:0 10px 24px rgba(0,0,0,.10);
    }
    *{ font-family:"DM Sans",system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif; }
    body{ color:var(--ink); }
    /* ===== HERO ===== */
    .pricing-hero{
      overflow:hidden;
      padding:72px 0 56px;
      background:
        radial-gradient(900px 420px at 85% -120px, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        radial-gradient(700px 380px at -10% 110%, rgba(0,0,0,.06) 0%, rgba(0,0,0,0) 60%),
        linear-gradient(180deg, #fbfbfc 0%, #f8f9fb 38%, #ffffff 100%);
    }
    .pricing-hero .lead{ color:var(--muted); }

    /* Sections */
    .section-title.centered{ text-align:center; }
    .section-subtitle{ color:var(--muted); text-align:center; }
    .pricing-section{ background:var(--section); border-top:1px solid var(--line); border-bottom:1px solid var(--line); padding:64px 0; }
    .comparison-section, .testimonial-section, .faq-section{ padding:64px 0; }

    /* Plan cards */
    .plan{
      background:var(--panel);
      border:1px solid var(--line);
      border-radius:var(--radius);
      padding:28px 22px;
      height:100%;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .plan:hover{ transform:translateY(-4px); box-shadow:var(--shadow-lg); border-color:#dcdcdc; }
    .plan-title{ font-weight:700; margin-bottom:.35rem; }
    .plan-price{ font-size:38px; font-weight:800; margin-bottom:.35rem; }
    .plan .text-muted{ color:var(--muted)!important; }
    .plan-features{ list-style:none; padding-left:0; margin:1rem 0 1.25rem; }
    .plan-features li{ margin:.45rem 0; display:flex; align-items:center; gap:.5rem; }
    .check{ color:#111; }
    .plan.featured{ border-color:#111; box-shadow:var(--shadow-lg); position:relative; }
    .plan.featured::before{
      content:'Most Popular';
      position:absolute; top:-12px; right:22px;
      background:#111; color:#fff; font-size:.72rem; font-weight:800;
      padding:.18rem .75rem; border-radius:999px;
    }

    /* Comparison Table */
    .comparison-table .table{
      background:#fff;
      border:1px solid var(--line);
      border-radius:var(--radius);
      overflow:hidden;
      box-shadow:var(--shadow);
    }
    .comparison-table thead th{
      background:#f8f9fb;
      color:#111;
      border-bottom:1px solid var(--line);
    }
    .comparison-table tbody td{
      vertical-align:middle;
      border-color:var(--line);
    }

    /* Testimonials */
    .testimonial-card{
      background:#fff;
      border:1px solid var(--line);
      border-radius:var(--radius);
      padding:22px;
      height:100%;
      box-shadow:var(--shadow);
      transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .testimonial-card:hover{ transform:translateY(-3px); box-shadow:var(--shadow-lg); border-color:#dcdcdc; }
    .testimonial-text p{ color:#111; margin-bottom:1rem; }
    .testimonial-author{ display:flex; align-items:center; gap:.75rem; }
    .testimonial-avatar{
      width:40px; height:40px; border-radius:999px;
      display:grid; place-items:center;
      background:#f3f4f6; border:1px solid var(--line); font-weight:700; color:#111;
    }

    /* FAQ */
    .faq-item{
      background:#fff; border:1px solid var(--line); border-radius:var(--radius);
      padding:18px; margin-bottom:12px; box-shadow:var(--shadow);
    }
    .faq-question{ font-weight:700; display:flex; align-items:center; gap:.6rem; }
    .faq-question i{ color:#111; }
    .faq-section .text-muted{ color:var(--muted)!important; }

    /* Responsive */
    @media (max-width: 768px){
      .pricing-hero{ padding:56px 0 40px; text-align:center; }
    }
  </style>
@endpush

@section('content')
  {{-- HERO --}}
  <section class="pricing-hero">
    <div class="container position-relative">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h1 class="display-5 fw-bold mb-3">Simple, Transparent Pricing</h1>
          <p class="lead text-muted mb-4">Choose the plan that works best for you. All plans include our core features with no hidden fees.</p>
          <div class="d-flex justify-content-center gap-3">
            <a href="#pricing" class="btn btn-brand btn-lg">View Plans</a>
            <a href="#faq" class="btn btn-ghost btn-lg">See FAQ</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- PRICING --}}
  <section id="pricing" class="pricing-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Plans for Every Need</h2>
        <p class="section-subtitle">Start free. Upgrade when you're ready.</p>
      </div>

      <div class="row g-4">
        {{-- Free --}}
        <div class="col-md-6 col-lg-4">
          <div class="plan">
            <h5 class="plan-title">Free</h5>
            <div class="plan-price">$0<span class="fs-6 text-muted">/mo</span></div>
            <p class="text-muted">Perfect for individuals getting started</p>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>500 MB storage</li>
              <li><i class="bi bi-check2 check"></i>Basic analytics</li>
              <li><i class="bi bi-check2 check"></i>Embed viewer</li>
              <li><i class="bi bi-check2 check"></i>Up to 10 documents</li>
              <li><i class="bi bi-check2 check"></i>Standard support</li>
              <li><i class="bi bi-x text-muted"></i>Permission controls</li>
              <li><i class="bi bi-x text-muted"></i>Custom branding</li>
              <li><i class="bi bi-x text-muted"></i>Advanced analytics</li>
            </ul>
            <a href="{{ url('/register') }}" class="btn btn-ghost w-100">Start free</a>
          </div>
        </div>

        {{-- Pro --}}
        <div class="col-md-6 col-lg-4">
          <div class="plan featured">
            <h5 class="plan-title">Pro</h5>
            <div class="plan-price">$12<span class="fs-6 text-muted">/mo</span></div>
            <p class="text-muted">Ideal for professionals and small teams</p>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>10 GB storage</li>
              <li><i class="bi bi-check2 check"></i>Advanced analytics</li>
              <li><i class="bi bi-check2 check"></i>Disable download/print</li>
              <li><i class="bi bi-check2 check"></i>Custom watermark</li>
              <li><i class="bi bi-check2 check"></i>Link expiry &amp; revocation</li>
              <li><i class="bi bi-check2 check"></i>Unlimited documents</li>
              <li><i class="bi bi-check2 check"></i>Priority support</li>
              <li><i class="bi bi-x text-muted"></i>Team collaboration</li>
            </ul>
            <a href="{{ url('/register') }}" class="btn btn-brand w-100">Choose Pro</a>
          </div>
        </div>

        {{-- Business --}}
        <div class="col-md-6 col-lg-4">
          <div class="plan">
            <h5 class="plan-title">Business</h5>
            <div class="plan-price">$29<span class="fs-6 text-muted">/mo</span></div>
            <p class="text-muted">For organizations with advanced needs</p>
            <ul class="plan-features">
              <li><i class="bi bi-check2 check"></i>Unlimited storage</li>
              <li><i class="bi bi-check2 check"></i>SSO, API &amp; webhooks</li>
              <li><i class="bi bi-check2 check"></i>OCR &amp; full-text search</li>
              <li><i class="bi bi-check2 check"></i>Domain/IP allowlists</li>
              <li><i class="bi bi-check2 check"></i>Team collaboration</li>
              <li><i class="bi bi-check2 check"></i>Custom branding</li>
              <li><i class="bi bi-check2 check"></i>Advanced permissions</li>
              <li><i class="bi bi-check2 check"></i>Dedicated support</li>
            </ul>
            <a href="{{ url('/contact') }}" class="btn btn-ghost w-100">Talk to sales</a>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <p class="text-muted">Need a custom plan? <a href="{{ url('/contact') }}">Contact us</a> for enterprise solutions.</p>
      </div>
    </div>
  </section>

  {{-- COMPARISON --}}
  <section class="comparison-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Plan Comparison</h2>
        <p class="section-subtitle">See how our plans stack up against each other</p>
      </div>

      <div class="comparison-table">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Feature</th>
              <th scope="col" class="text-center">Free</th>
              <th scope="col" class="text-center">Pro</th>
              <th scope="col" class="text-center">Business</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Storage</td><td class="text-center">500 MB</td><td class="text-center">10 GB</td><td class="text-center">Unlimited</td></tr>
            <tr><td>Documents</td><td class="text-center">Up to 10</td><td class="text-center">Unlimited</td><td class="text-center">Unlimited</td></tr>
            <tr><td>Download prevention</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Print prevention</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Custom watermarks</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Link expiration</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Advanced analytics</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>API access</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Team collaboration</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
            <tr><td>Priority support</td><td class="text-center"><i class="bi bi-x text-muted"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td><td class="text-center"><i class="bi bi-check2 check"></i></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  {{-- TESTIMONIALS --}}
  <section class="testimonial-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Loved by Professionals</h2>
        <p class="section-subtitle">See what our customers are saying about PDFOneLink</p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              <p>PDFOneLink has transformed how we share sensitive documents with clients. The analytics help us understand engagement, and the security features give us peace of mind.</p>
            </div>
            <div class="testimonial-author">
              <div class="testimonial-avatar">SA</div>
              <div>
                <h6 class="mb-0">Sarah Anderson</h6>
                <p class="text-muted small mb-0">Legal Consultant</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              <p>The ability to control who can view, download, or print our documents has been a game-changer for our business. The pricing is fair and the platform is incredibly reliable.</p>
            </div>
            <div class="testimonial-author">
              <div class="testimonial-avatar">MJ</div>
              <div>
                <h6 class="mb-0">Michael Johnson</h6>
                <p class="text-muted small mb-0">Marketing Director</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="testimonial-text">
              <p>We switched to PDFOneLink from another service and couldn't be happier. The embed features work seamlessly on our website, and our clients find the viewer intuitive to use.</p>
            </div>
            <div class="testimonial-author">
              <div class="testimonial-avatar">ER</div>
              <div>
                <h6 class="mb-0">Emily Rodriguez</h6>
                <p class="text-muted small mb-0">CTO, Tech Startup</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  {{-- FAQ --}}
  <section id="faq" class="faq-section">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title centered">Frequently Asked Questions</h2>
        <p class="section-subtitle">Everything you need to know about our pricing</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-credit-card"></i> What payment methods do you accept?</h6>
            <p class="text-muted mb-0">We accept all major credit cards, PayPal, and bank transfers for annual plans. All payments are processed securely through our payment partners.</p>
          </div>

          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-arrow-left-right"></i> Can I change my plan later?</h6>
            <p class="text-muted mb-0">Yes, you can upgrade or downgrade your plan at any time. When upgrading, the new rate will be prorated. When downgrading, changes take effect at the next billing cycle.</p>
          </div>

          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-x-circle"></i> Is there a cancellation fee?</h6>
            <p class="text-muted mb-0">No, there are no cancellation fees. You can cancel your subscription at any time, and you'll continue to have access until the end of your billing period.</p>
          </div>

          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-receipt"></i> Do you offer refunds?</h6>
            <p class="text-muted mb-0">We offer a 14-day money-back guarantee for all annual plans. If you're not satisfied, contact our support team for a full refund.</p>
          </div>

          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-building"></i> Do you offer discounts for nonprofits?</h6>
            <p class="text-muted mb-0">Yes, we offer a 20% discount for registered nonprofit organizations. Contact our sales team with proof of your nonprofit status to get started.</p>
          </div>

          <div class="faq-item">
            <h6 class="faq-question"><i class="bi bi-people"></i> Do you have team pricing?</h6>
            <p class="text-muted mb-0">Yes, our Business plan includes team features. For larger organizations, we offer enterprise pricing with custom terms. Contact us for more information.</p>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <p class="text-muted">Still have questions? <a href="{{ url('/contact') }}">Contact our team</a> for more information.</p>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="pricing-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="mb-4">Ready to get started?</h2>
          <p class="text-muted mb-4">Join thousands of professionals who trust PDFOneLink with their document sharing needs.</p>
          <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ url('/register') }}" class="btn btn-brand btn-lg">Start free trial</a>
            <a href="{{ url('/contact') }}" class="btn btn-ghost btn-lg">Contact sales</a>
          </div>
          <p class="text-muted small mt-3">No credit card required. Try all features free for 14 days.</p>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    // (Optional) fill a year element if you use one in your footer partial
    (function(){ var y=document.getElementById('y'); if(y) y.textContent=new Date().getFullYear(); })();
  </script>
@endpush
