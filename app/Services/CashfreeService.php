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

        [$appId, $secret] = $this->credentials();

        return $appId !== '' && $secret !== '';
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

        [$appId, $secret] = $this->credentials(true);

        $this->client = Http::baseUrl($this->baseUrl())
            ->withHeaders($this->authenticationHeaders($appId, $secret))
            ->acceptJson();

        return $this->client;
    }

    protected function credentials(bool $strict = false): array
    {
        $appId = trim((string) config('cashfree.app_id'));
        $secret = trim((string) config('cashfree.secret_key'));

        if ($strict && ($appId === '' || $secret === '')) {
            throw new RuntimeException('Cashfree credentials are missing.');
        }

        return [$appId, $secret];
    }

    protected function authenticationHeaders(string $appId, string $secret): array
    {
        $headers = [
            'x-client-id' => $appId,
            'x-client-secret' => $secret,
            'x-api-version' => $this->apiVersion(),
        ];

        $headers['Authorization'] = 'Basic ' . base64_encode("{$appId}:{$secret}");

        return $headers;
    }

    /**
     * Create a payment order with Cashfree.
     */
    public function createOrder(array $payload): array
    {
        $response = $this->client()->post('orders', $payload);

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
        $response = $this->client()->get("orders/{$orderId}");

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
