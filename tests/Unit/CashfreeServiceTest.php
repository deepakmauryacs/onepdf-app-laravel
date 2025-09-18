<?php

namespace Tests\Unit;

use App\Services\CashfreeService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CashfreeServiceTest extends TestCase
{
    public function test_create_order_uses_trimmed_credentials_and_basic_authentication(): void
    {
        config([
            'cashfree.enabled' => true,
            'cashfree.app_id' => ' test-app ',
            'cashfree.secret_key' => " test-secret \n",
            'cashfree.environment' => 'sandbox',
            'cashfree.api_version' => '2022-09-01',
        ]);

        $capturedRequest = null;

        Http::fake([
            'https://sandbox.cashfree.com/pg/orders' => function ($request) use (&$capturedRequest) {
                $capturedRequest = $request;

                return Http::response([
                    'order_id' => 'ORD123',
                    'payment_session_id' => 'SESSION123',
                ], 200);
            },
        ]);

        $service = new CashfreeService();

        $payload = [
            'order_id' => 'ORD123',
            'order_amount' => 10,
            'order_currency' => 'INR',
            'customer_details' => ['customer_id' => 'customer-1'],
        ];

        $response = $service->createOrder($payload);

        $this->assertSame('ORD123', $response['order_id']);
        $this->assertNotNull($capturedRequest, 'Cashfree request was not captured.');

        $this->assertTrue($capturedRequest->hasHeader('x-client-id', 'test-app'));
        $this->assertTrue($capturedRequest->hasHeader('x-client-secret', 'test-secret'));
        $this->assertTrue($capturedRequest->hasHeader('x-api-version', '2022-09-01'));

        $this->assertSame(
            ['Basic ' . base64_encode('test-app:test-secret')],
            $capturedRequest->header('Authorization')
        );

        Http::assertSentCount(1);
    }
}
