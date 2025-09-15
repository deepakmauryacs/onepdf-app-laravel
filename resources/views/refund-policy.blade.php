@extends('layouts.app')

@section('title', 'Refund Policy - OneLinkPDF')

@section('content')
<section class="section py-5">
  <div class="container">
    <h1 class="section-title text-center mb-2">Refund Policy</h1>
    <p class="section-subtitle text-center text-muted mb-4">
      Effective Date: {{ now()->format('F d, Y') }}
    </p>

    <p>This Refund Policy is an agreement between you (“<strong>Customer</strong>”) and <strong>OneLinkPDF </strong> (“<strong>OneLinkPDF</strong>”, “we”, “us”, “our”), owner and operator of
      <a href="https://www.onelinkpdf.com" rel="nofollow">https://www.onelinkpdf.com</a> (“<strong>Website</strong>”). It governs refund requests for subscriptions purchased through the Website and should be read together with our
      <a href="{{ url('/terms') }}">Terms of Service</a> and <a href="{{ url('/privacy') }}">Privacy Policy</a>.
    </p>

    <p>We may update this Refund Policy from time to time. Continued use of the Services after changes take effect constitutes acceptance of the revised policy. This Refund Policy does not affect your statutory rights under applicable law.</p>

    <hr class="my-5"/>

    <h5>1) Refund Eligibility</h5>
    <ol class="mb-4">
      <li class="mb-2">
        <strong>Subscriptions (recurring fees).</strong> We will consider refunds for the <em>first</em> billing cycle’s recurring subscription fee on paid plans if:
        <ul>
          <li>You submit a written refund request within <strong>14 days</strong> from the initial purchase/activation date; and</li>
          <li>You provide a valid reason describing why the Service did not meet your needs.</li>
        </ul>
      </li>
      <li class="mb-2">
        <strong>Non-refundable fees.</strong> The following are <em>non-refundable</em> unless required by law:
        <ul>
          <li>One-time setup/activation/onboarding fees (if applicable);</li>
          <li>Add-ons, usage overages, and metered charges already incurred;</li>
          <li>Fees for renewed billing periods once the renewal date has passed;</li>
          <li>Taxes, foreign exchange or bank charges collected by third parties.</li>
        </ul>
      </li>
      <li class="mb-2">
        <strong>Plan changes & cancellations.</strong> Downgrades or cancellations take effect at the end of the current billing period. We do not provide prorated refunds for partial months after the 14-day window.
      </li>
    </ol>

    <h5>2) Refund Procedure</h5>
    <ol class="mb-4">
      <li class="mb-2">
        <strong>Submit request.</strong> Email <a href="mailto:support@onelinkpdf.com">support@onelinkpdf.com</a> (or use the
        <a href="{{ url('/contact') }}">contact page</a>) within 14 days of purchase. Include:
        <ul>
          <li>Account email / workspace URL,</li>
          <li>Order ID or invoice number,</li>
          <li>Purchase date, and</li>
          <li>A brief reason for the refund request.</li>
        </ul>
      </li>
      <li class="mb-2">
        <strong>Review & confirmation.</strong> We’ll acknowledge receipt and evaluate your request case-by-case against this Policy.
      </li>
      <li class="mb-2">
        <strong>Method & timeline.</strong> Approved refunds are issued to the original payment method (or another mutually agreed method) and are typically processed within <strong>14 business days</strong> from approval. Your bank or payment provider may take additional time to post the funds.
      </li>
    </ol>

    <h5>3) Renewals & Trials</h5>
    <ul class="mb-4">
      <li><strong>Auto-renewals.</strong> To avoid being charged for the next term, cancel before the renewal date shown in your billing settings.</li>
      <li><strong>Trials.</strong> If your plan started on a free trial and you were charged after the trial ended, refund requests follow Section 1.</li>
    </ul>

    <h5>4) Payments via Third Parties</h5>
    <p>Payments are handled by our processors (e.g., Stripe, PayPal, Razorpay). Any processor-specific fees or timelines are subject to their policies. We may need additional time to coordinate with them for resolution.</p>

    <h5>5) Abuse & Chargebacks</h5>
    <p>We’re happy to help resolve billing issues. Initiating a chargeback without contacting us may delay resolution. We reserve the right to suspend or terminate accounts involved in suspected fraud or abuse of the refund process.</p>

    <h5>6) Contact</h5>
    <p>If you have questions about this Refund Policy or wish to submit a request, contact:</p>
    <ul>
      <li>Email: <a href="mailto:support@onelinkpdf.com">support@onelinkpdf.com</a></li>
      <li>Contact page: <a href="{{ url('/contact') }}">{{ url('/contact') }}</a></li>
      <li>Address: A-152,Sector 53, Noida, Uttar Pradesh 201307</li>
    </ul>

    <hr class="my-5"/>

    <p class="small text-muted mb-0">Note: Submission of a refund request does not guarantee a refund. OneLinkPDF evaluates each request in good faith under this Policy and applicable law.</p>
  </div>
</section>
@endsection

