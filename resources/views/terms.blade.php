@extends('layouts.app')

@section('title', 'Terms of Service - OneLinkPDF')

@section('content')
<section class="section py-5">
  <div class="container">
    <h1 class="section-title text-center mb-3">Terms of Service</h1>
    <p class="section-subtitle text-center text-muted mb-4">Effective Date: {{ now()->format('F d, Y') }}</p>

    <p>Welcome to OneLinkPDF (“<strong>OneLinkPDF</strong>”, “<strong>we</strong>”, “<strong>us</strong>”, or “<strong>our</strong>”). These Terms of Service (“<strong>Terms</strong>”) govern your access to and use of our website <a href="https://www.onelinkpdf.com" rel="nofollow">https://www.onelinkpdf.com</a>, applications, and services (collectively, the “<strong>Services</strong>”). OneLinkPDF is owned and operated by <strong>OneLinkPDF.COM</strong> (“Company”).</p>

    <p>By accessing or using the Services, you agree to be bound by these Terms, our <a href="{{ url('privacy') }}">Privacy Policy</a>, and, where applicable, our Refund/Cancellation Policy. If you do not agree, you must not use the Services.</p>

    <hr class="my-5"/>

    <h5>1) Acceptance of Terms</h5>
    <p>These Terms constitute a legally binding agreement between you and OneLinkPDF. We may update these Terms from time to time. Changes are effective upon posting on the site. Your continued use of the Services after changes means you accept the updated Terms.</p>

    <h5>2) Eligibility</h5>
    <p>You may use the Services only if you can form a binding contract under applicable law and are not barred from using the Services under any applicable laws. The Services are not intended for children under 13. If you are 13–18, you may use the Services only with involvement of a parent or legal guardian.</p>

    <h5>3) What OneLinkPDF Does</h5>
    <p>OneLinkPDF is a secure document-sharing SaaS that enables users to upload PDFs, create protected share links, apply access controls (e.g., watermarking, domain/IP lock, expiry), and view analytics. We continuously improve the platform and may add, change, or remove features at our discretion.</p>

    <h5>4) Accounts & Security</h5>
    <ul>
      <li>You are responsible for maintaining the confidentiality of your account credentials and all activities that occur under your account.</li>
      <li>Notify us immediately of any unauthorized use or security breach. We are not liable for losses caused by unauthorized use of your account.</li>
      <li>We may suspend or terminate accounts that violate these Terms, provide false information, or interfere with others’ use of the Services.</li>
    </ul>

    <h5>5) Subscriptions, Billing & Auto-Renewal</h5>
    <ul>
      <li>We offer Free and paid plans (e.g., Monthly or Yearly). Plan features and prices are shown at checkout or on our pricing page.</li>
      <li>Paid plans renew automatically on the applicable billing date until cancelled. By subscribing, you authorize us (and our payment processors) to charge the recurring fees and applicable taxes to your payment method.</li>
      <li>Cancel anytime prior to the next renewal to avoid future charges. Upon cancellation, paid features may become unavailable at the end of the current billing period.</li>
      <li>We may change plan prices or features with notice. Changes take effect on your next billing cycle unless otherwise stated.</li>
    </ul>

    <h5>6) Payments</h5>
    <p>Payments are processed by third-party providers (e.g., Stripe, PayPal, Razorpay, etc.). By submitting payment information, you represent that you are authorized to use the chosen payment method and authorize the charge for subscription fees and taxes. Transaction receipts will be sent to your registered email.</p>

    <h5>7) License to Use the Software</h5>
    <p>Subject to these Terms and your plan, OneLinkPDF grants you a limited, non-exclusive, non-transferable, revocable license to access and use the Services for your internal business or personal purposes. You may not sublicense, distribute, modify, reverse engineer, or use the Services in any way not expressly permitted by these Terms or applicable law.</p>

    <h5>8) Prohibited Uses</h5>
    <ul>
      <li>Copying, scraping, or harvesting the Services or content (except as expressly permitted), or using bots or automated means without permission.</li>
      <li>Attempting to bypass security, usage rules, rate limits, or DRM/anti-copy mechanisms.</li>
      <li>Uploading unlawful, infringing, harmful, or malicious content, or violating third-party rights.</li>
      <li>Using the Services to host or distribute malware, to harass, or to engage in illegal activities.</li>
    </ul>

    <h5>9) Your Content (“Materials”)</h5>
    <ul>
      <li>You retain ownership of PDFs and other content you upload (“<strong>Materials</strong>”). You are solely responsible for the accuracy, legality, and rights to your Materials.</li>
      <li>You grant OneLinkPDF a worldwide, non-exclusive, royalty-free license to host, process, transmit, display, and create derivative works (solely for technical operations such as encryption, watermark rendering, thumbnails, analytics) to provide and improve the Services.</li>
      <li>You represent that you have all rights to grant this license and that your Materials do not infringe third-party rights.</li>
      <li>We may remove Materials that violate these Terms or applicable law.</li>
    </ul>

    <h5>10) Intellectual Property</h5>
    <p>All intellectual property in the Services (software, UI, design, databases, trademarks, logos, and content excluding your Materials) is owned by or licensed to OneLinkPDF and protected by applicable laws. No rights are granted except as expressly set out in these Terms.</p>

    <h5>11) Third-Party Materials & Links</h5>
    <p>The Services may display or link to third-party content, tools, or services. We do not control and are not responsible for such third-party materials. Your use of third-party services is subject to their terms and policies.</p>

    <h5>12) Service Changes; Maintenance & Support</h5>
    <ul>
      <li>We may release updates, enhancements, or discontinue features at any time. We aim to schedule maintenance to minimize disruption.</li>
      <li>We provide reasonable technical support for supported environments, typically via email or in-app channels. We do not guarantee resolution times or that the Services will be error-free or uninterrupted.</li>
    </ul>

    <h5>13) Data Protection & Security</h5>
    <ul>
      <li>We implement reasonable technical and organizational measures to protect personal data and sensitive information (e.g., encryption in transit; at-rest measures where applicable).</li>
      <li>Analytics, access logs, and share-link telemetry may be processed to provide insights and security.</li>
      <li>Your use of the Services is also governed by our Privacy Policy.</li>
    </ul>

    <h5>14) Accuracy & Availability</h5>
    <p>We do not warrant that information on the site is complete, accurate, or current, or that the Services will be available at all times. Use is at your own risk.</p>

    <h5>15) Disclaimer of Warranties</h5>
    <p>THE SERVICES ARE PROVIDED “AS IS” AND “AS AVAILABLE” WITHOUT WARRANTIES OF ANY KIND, WHETHER EXPRESS, IMPLIED, OR STATUTORY, INCLUDING WITHOUT LIMITATION IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. WE DO NOT WARRANT THAT THE SERVICES WILL BE UNINTERRUPTED, SECURE, OR ERROR-FREE.</p>

    <h5>16) Limitation of Liability</h5>
    <p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, IN NO EVENT WILL ONELINKPDF OR ITS AFFILIATES BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, EXEMPLARY, OR PUNITIVE DAMAGES (INCLUDING LOSS OF USE, DATA, PROFITS, OR BUSINESS INTERRUPTION), ARISING OUT OF OR IN CONNECTION WITH YOUR USE OF (OR INABILITY TO USE) THE SERVICES, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. OUR TOTAL LIABILITY FOR ALL CLAIMS RELATING TO THE SERVICES SHALL NOT EXCEED THE AMOUNT YOU PAID TO US FOR THE SERVICES IN THE TWELVE (12) MONTHS PRECEDING THE EVENT GIVING RISE TO THE CLAIM.</p>

    <h5>17) Indemnification</h5>
    <p>You agree to indemnify, defend, and hold harmless OneLinkPDF and its affiliates, officers, directors, employees, and agents from any claims, damages, liabilities, costs, and expenses (including reasonable attorneys’ fees) arising from your (a) use of the Services, (b) Materials, (c) violation of these Terms or applicable law, or (d) infringement of any third-party rights.</p>

    <h5>18) Suspension & Termination</h5>
    <ul>
      <li>You may cancel your subscription at any time via your account settings. Deleting your account removes access to the Services; we may retain certain data as required by law or legitimate business needs.</li>
      <li>We may suspend or terminate your access immediately for violations of these Terms, suspected fraud, legal requests, or security risks.</li>
    </ul>

    <h5>19) Communications & Notices</h5>
    <p>By creating an account, you consent to receive transactional emails (e.g., receipts, service updates). You may opt out of non-essential marketing emails via provided methods. We are not responsible for failures in email delivery due to factors outside our control.</p>

    <h5>20) Assignment</h5>
    <p>You may not assign or transfer these Terms without our prior written consent. We may assign our rights and obligations under these Terms in connection with a merger, acquisition, or sale of assets.</p>

    <h5>21) Governing Law & Dispute Resolution</h5>
    <p>These Terms are governed by the laws of India. Subject to applicable law, you agree that courts located in Uttar Pradesh, India shall have exclusive jurisdiction over all disputes arising out of or relating to these Terms or the Services.</p>

    <h5>22) Entire Agreement; Severability; Waiver; Headings</h5>
    <p>These Terms, together with the Privacy Policy and any plan-specific terms, constitute the entire agreement between you and OneLinkPDF. If any provision is found unenforceable, the remaining provisions will remain in full force. Our failure to enforce any provision is not a waiver. Headings are for convenience only.</p>

    <h5>23) Your Responsibilities</h5>
    <ul>
      <li>Provide accurate account information and maintain compliance with applicable laws (including IP, privacy, and consumer protection laws) when sharing content.</li>
      <li>Ensure you have the necessary rights and lawful basis to upload and share Materials via the Services.</li>
      <li>Comply with storage limits, fair-use, and technical restrictions associated with your plan.</li>
    </ul>

    <h5>24) Refunds & Cancellations</h5>
    <p>Any refunds, if applicable, are subject to our Refund/Cancellation Policy (if available) and applicable law. Unless stated otherwise, fees are non-refundable.</p>

    <h5>25) Service-Level & Previous Releases</h5>
    <p>We aim to provide a consistent level of service; however, we do not guarantee uptime or response times. We may discontinue support for older releases or features following reasonable notice.</p>

    <h5>26) Security Components</h5>
    <p>The Services may include security controls (e.g., encryption, tokenized links, DRM-style restrictions). You agree not to disable, bypass, or interfere with such controls.</p>

    <h5>27) Compliance</h5>
    <p>You agree to use the Services in compliance with all applicable laws and regulations, including data protection, export control, and sanctions laws where relevant.</p>

    <h5>28) Contact</h5>
    <p>If you have questions about these Terms, please contact us at <a href="mailto:support@onelinkpdf.com">support@onelinkpdf.com</a></p>

    <h5>29) Final Provisions</h5>
    <p>Use of the Services is unauthorized in any jurisdiction that does not give effect to these Terms. We may comply with lawful requests by authorities regarding your use of the Services.</p>

    <h5>30) Changes to Services or Policies</h5>
    <p>We may revise features, plans, and policies from time to time. Where required, we will provide notice via the site or email. Continued use after the effective date constitutes acceptance.</p>

    <hr class="my-5"/>

    <p class="small text-muted">Legal note: This document is a general template tailored for a SaaS product and does not constitute legal advice. Consider having counsel review to ensure compliance with your specific operations, jurisdictions, and payment flows.</p>
  </div>
</section>
@endsection
