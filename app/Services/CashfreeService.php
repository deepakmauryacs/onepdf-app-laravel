<?php

namespace App\Services;

use Cashfree\ApiException;
use Cashfree\Cashfree as CashfreeClient;
use Cashfree\Model\ModelInterface;
use Cashfree\ObjectSerializer;
use JsonSerializable;
use RuntimeException;
use Throwable;

class CashfreeService
{
    protected const MINIMUM_API_VERSION = '2025-01-01';

    protected ?CashfreeClient $sdk = null;

    public function __construct(?CashfreeClient $sdk = null)
    {
        $this->sdk = $sdk;
    }

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
        $configured = trim((string) config('cashfree.api_version', ''));

        if ($configured === '') {
            return static::MINIMUM_API_VERSION;
        }

        return version_compare($configured, static::MINIMUM_API_VERSION, '<')
            ? static::MINIMUM_API_VERSION
            : $configured;
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

    /**
     * Create a payment order with Cashfree.
     */
    public function createOrder(array $payload): array
    {
        $client = $this->client();

        try {
            $result = $client->PGCreateOrder($this->preparePayload($payload));
        } catch (ApiException $exception) {
            $message = $this->messageFromSdkException($exception, 'Failed to create Cashfree order.');

            throw new RuntimeException($message ?: 'Failed to create Cashfree order.', 0, $exception);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage() ?: 'Failed to create Cashfree order.', 0, $throwable);
        }

        [$order, $status] = [$result[0] ?? [], (int) ($result[1] ?? 0)];

        if ($status < 200 || $status >= 300) {
            $message = $this->extractErrorMessage($this->convertToArray($order), 'Failed to create Cashfree order.');

            throw new RuntimeException($message ?: 'Failed to create Cashfree order.');
        }

        return $this->convertToArray($order);
    }

    /**
     * Fetch an existing order from Cashfree.
     */
    public function getOrder(string $orderId): array
    {
        $client = $this->client();

        try {
            $result = $client->PGFetchOrder(trim($orderId));
        } catch (ApiException $exception) {
            $message = $this->messageFromSdkException($exception, 'Failed to fetch Cashfree order details.');

            throw new RuntimeException($message ?: 'Failed to fetch Cashfree order details.', 0, $exception);
        } catch (Throwable $throwable) {
            throw new RuntimeException($throwable->getMessage() ?: 'Failed to fetch Cashfree order details.', 0, $throwable);
        }

        [$order, $status] = [$result[0] ?? [], (int) ($result[1] ?? 0)];

        if ($status < 200 || $status >= 300) {
            $message = $this->extractErrorMessage($this->convertToArray($order), 'Failed to fetch Cashfree order details.');

            throw new RuntimeException($message ?: 'Failed to fetch Cashfree order details.');
        }

        return $this->convertToArray($order);
    }

    protected function client(): CashfreeClient
    {
        [$appId, $secret] = $this->credentials(true);
        $environment = $this->mode() === 'production' ? 1 : 0;

        if (! $this->sdk instanceof CashfreeClient) {
            $this->sdk = new CashfreeClient(
                $environment,
                $appId,
                $secret,
                '',
                '',
                '',
                false
            );
        }

        $this->sdk->XClientId = $appId;
        $this->sdk->XClientSecret = $secret;
        $this->sdk->XEnvironment = $environment;
        $this->sdk->XApiVersion = $this->apiVersion();
        $this->sdk->XEnableErrorAnalytics = false;

        return $this->sdk;
    }

    protected function preparePayload(array $payload): array
    {
        return $this->filterNullValues($payload);
    }

    protected function filterNullValues(array $data): array
    {
        $filtered = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $value = $this->filterNullValues($value);

                if ($value === []) {
                    continue;
                }
            }

            $filtered[$key] = $value;
        }

        return $filtered;
    }

    protected function convertToArray(mixed $response): array
    {
        if ($response instanceof ModelInterface) {
            $sanitized = ObjectSerializer::sanitizeForSerialization($response);
            $decoded = json_decode(json_encode($sanitized), true);

            return is_array($decoded) ? $decoded : [];
        }

        if ($response instanceof JsonSerializable) {
            $encoded = json_encode($response);
            $decoded = is_string($encoded) ? json_decode($encoded, true) : null;

            return is_array($decoded) ? $decoded : [];
        }

        if ($response instanceof \stdClass) {
            $decoded = json_decode(json_encode($response), true);

            return is_array($decoded) ? $decoded : [];
        }

        if (is_array($response)) {
            return $response;
        }

        if (is_string($response)) {
            $decoded = json_decode($response, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    protected function messageFromSdkException(ApiException $exception, string $fallback): string
    {
        $responseObject = $exception->getResponseObject();

        if ($responseObject !== null) {
            $message = $this->extractErrorMessage($this->convertToArray($responseObject), '');

            if ($message !== '') {
                return $message;
            }
        }

        $responseBody = $exception->getResponseBody();

        if ($responseBody !== null) {
            $message = $this->extractErrorMessage($this->convertToArray($responseBody), '');

            if ($message !== '') {
                return $message;
            }

            if (is_string($responseBody) && trim($responseBody) !== '') {
                return $responseBody;
            }
        }

        $message = $exception->getMessage();

        return is_string($message) && trim($message) !== '' ? $message : $fallback;
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
