<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CashfreeService
{
    protected ?PendingRequest $client = null;

    public function enabled(): bool
    {
        if (!config('cashfree.enabled', true)) {
            return false;
        }

        return filled(config('cashfree.app_id')) && filled(config('cashfree.secret_key'));
    }

    public function mode(): string
    {
        $mode = strtolower((string) config('cashfree.environment', 'sandbox'));

        return $mode === 'production' ? 'production' : 'sandbox';
    }

    public function orderPrefix(): string
    {
        return (string) config('cashfree.order_prefix', 'ONELINK');
    }

    protected function baseUrl(): string
    {
        $mode = $this->mode();
        $base = config("cashfree.base_urls.{$mode}");

        if (! $base) {
            throw new RuntimeException('Cashfree base URL is not configured.');
        }

        return $base;
    }

    protected function apiVersion(): string
    {
        return (string) config('cashfree.api_version', '2022-09-01');
    }

    protected function client(): PendingRequest
    {
        if ($this->client instanceof PendingRequest) {
            return $this->client;
        }

        $appId = config('cashfree.app_id');
        $secret = config('cashfree.secret_key');

        if (! $appId || ! $secret) {
            throw new RuntimeException('Cashfree credentials are missing.');
        }

        $this->client = Http::baseUrl($this->baseUrl())
            ->withHeaders([
                'x-client-id' => $appId,
                'x-client-secret' => $secret,
                'x-api-version' => $this->apiVersion(),
            ])
            ->acceptJson();

        return $this->client;
    }

    /**
     * Create a payment order with Cashfree.
     */
    public function createOrder(array $payload): array
    {
        $response = $this->client()->post('/orders', $payload);

        if ($response->failed()) {
            $message = $this->extractErrorMessage($response->json(), $response->body());
            throw new RuntimeException($message ?: 'Failed to create Cashfree order.');
        }

        return $response->json();
    }

    /**
     * Fetch an existing order from Cashfree.
     */
    public function getOrder(string $orderId): array
    {
        $response = $this->client()->get("/orders/{$orderId}");

        if ($response->failed()) {
            $message = $this->extractErrorMessage($response->json(), $response->body());
            throw new RuntimeException($message ?: 'Failed to fetch Cashfree order details.');
        }

        return $response->json();
    }

    protected function extractErrorMessage(?array $json, string $fallback = ''): string
    {
        if (is_array($json)) {
            if (! empty($json['message']) && is_string($json['message'])) {
                return $json['message'];
            }

            if (! empty($json['errors']) && is_array($json['errors'])) {
                return collect($json['errors'])
                    ->flatten()
                    ->filter()
                    ->map(fn ($message) => is_array($message) ? implode(' ', $message) : (string) $message)
                    ->implode(' ');
            }
        }

        return $fallback;
    }
}
