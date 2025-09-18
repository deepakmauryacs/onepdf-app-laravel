<?php

namespace Tests\Unit;

use App\Services\CashfreeService;
use Cashfree\Cashfree as CashfreeClient;
use Tests\TestCase;

if (! class_exists('Cashfree\\GuzzleHttp\\Client')) {
    class_alias(\GuzzleHttp\Client::class, 'Cashfree\\GuzzleHttp\\Client');
}

class CashfreeServiceTest extends TestCase
{
    public function test_create_order_configures_sdk_and_filters_payload(): void
    {
        config([
            'cashfree.enabled' => true,
            'cashfree.app_id' => ' test-app ',
            'cashfree.secret_key' => " test-secret \n",
            'cashfree.environment' => 'sandbox',
            'cashfree.api_version' => '2027-03-15',
        ]);

        $client = new class ([
            'create' => [
                [
                    'order_id' => 'ORD123',
                    'payment_session_id' => 'SESSION123',
                    'order_currency' => 'INR',
                ],
                200,
                [],
            ],
        ]) extends CashfreeClient {
            public array $calls = [];

            public function __construct(private array $responses)
            {
            }

            public function PGCreateOrder($payload, $x_request_id = null, $x_idempotency_key = null, ?\Cashfree\GuzzleHttp\Client $http_client = null)
            {
                $this->calls[] = ['method' => 'PGCreateOrder', 'payload' => $payload];

                return $this->responses['create'];
            }
        };

        $service = new CashfreeService($client);

        $payload = [
            'order_id' => 'ORD123',
            'order_amount' => 10,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'customer-1',
                'customer_phone' => '9999999999',
                'optional' => null,
            ],
            'unused' => null,
        ];

        $response = $service->createOrder($payload);

        $this->assertSame('ORD123', $response['order_id']);
        $this->assertSame('test-app', $client->XClientId);
        $this->assertSame('test-secret', $client->XClientSecret);
        $this->assertSame($client->SANDBOX, $client->XEnvironment);
        $this->assertSame('2027-03-15', $client->XApiVersion);

        $this->assertSame([
            'order_id' => 'ORD123',
            'order_amount' => 10,
            'order_currency' => 'INR',
            'customer_details' => [
                'customer_id' => 'customer-1',
                'customer_phone' => '9999999999',
            ],
        ], $client->calls[0]['payload']);
    }

    public function test_create_order_uses_minimum_supported_api_version_when_configured_value_is_outdated(): void
    {
        config([
            'cashfree.enabled' => true,
            'cashfree.app_id' => 'app',
            'cashfree.secret_key' => 'secret',
            'cashfree.environment' => 'sandbox',
            'cashfree.api_version' => '2022-09-01',
        ]);

        $client = new class ([
            'create' => [
                [
                    'order_id' => 'ORD123',
                    'payment_session_id' => 'SESSION123',
                ],
                200,
                [],
            ],
        ]) extends CashfreeClient {
            public array $calls = [];

            public function __construct(private array $responses)
            {
            }

            public function PGCreateOrder($payload, $x_request_id = null, $x_idempotency_key = null, ?\Cashfree\GuzzleHttp\Client $http_client = null)
            {
                $this->calls[] = ['method' => 'PGCreateOrder', 'payload' => $payload];

                return $this->responses['create'];
            }
        };

        $service = new CashfreeService($client);

        $service->createOrder([
            'order_id' => 'ORD123',
            'order_amount' => 10,
            'order_currency' => 'INR',
            'customer_details' => ['customer_id' => 'customer-1'],
        ]);

        $this->assertSame('2025-01-01', $client->XApiVersion);
    }
}
