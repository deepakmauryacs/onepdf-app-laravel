@extends('layouts.app')

@section('title', 'Refund Policy - OneLinkPDF')

@section('content')
<section class="section">
  <div class="container">
    <h1 class="section-title centered">Refund Policy</h1>
    <p class="section-subtitle">Transparency on refunds and cancellations.</p>

    <p>We want you to love using OneLinkPDF. If the service isn&apos;t the right fit, you can request a refund within 14 days of your initial purchase on paid plans.</p>
    <p>Refunds are evaluated on a case-by-case basis and are issued back to the original payment method once approved. To begin the process, please <a href="{{ url('contact') }}">contact our support team</a> with your account details and purchase receipt.</p>
    <p>After 14 days, refunds are not guaranteed, but we&apos;re always available to help you resolve any issues or adjust your plan so it better matches your needs.</p>
  </div>
</section>
@endsection
