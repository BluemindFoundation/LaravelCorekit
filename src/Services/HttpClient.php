<?php

namespace Corekit\Services;

use Corekit\Contracts\HttpClientInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class HttpClient implements HttpClientInterface
{
    protected int $timeout;
    protected int $maxRetries;

    public function __construct(int $timeout = 5, int $maxRetries = 1)
    {
        $this->timeout = $timeout;
        $this->maxRetries = $maxRetries;
    }

    /**
     * Synchronous HTTP request.
     * Returns a standardized array:
     * [
     *    'success' => bool,
     *    'status' => int,
     *    'data' => array|null,
     *    'error' => string|null,
     * ]
     */
    public function request(string $method, string $url, array $options = []): array
    {
        $attempt = 0;
        $errorMessage = null;

        do {
            $attempt++;

            try {
                $request = Http::timeout($this->timeout);

                if (!empty($options['headers'])) {
                    $request = $request->withHeaders($options['headers']);
                }

                if (!empty($options['query'])) {
                    $request = $request->withOptions(['query' => $options['query']]);
                }

                /** @var Response $response */
                $response = match (strtoupper($method)) {
                    'GET' => $request->get($url),
                    'POST' => $request->post($url, $options['json'] ?? []),
                    'PUT' => $request->put($url, $options['json'] ?? []),
                    'PATCH' => $request->patch($url, $options['json'] ?? []),
                    'DELETE' => $request->delete($url, $options['json'] ?? []),
                    default => throw new \InvalidArgumentException("HTTP method $method not supported"),
                };

                Log::info("[HttpClient] Attempt #$attempt - $method $url");
                Log::info("[HttpClient] Status: {$response->status()}");
                Log::info("[HttpClient] Response: " . $response->body());
                if ($response->successful()) {
                    return [
                        'success' => true,
                        'status' => $response->status(),
                        'data' => $response->json(),
                    ];
                }

                $errorMessage = "[HttpClient] Failed response body: {$response->body()}";
                Log::warning("[HttpClient] $errorMessage");
            } catch (RequestException | Throwable $e) {
                $errorMessage = "HTTP request exception: " . $e->getMessage();
                Log::error("[HttpClient] $errorMessage");
            }

            sleep($attempt ** 2);
        } while ($attempt < $this->maxRetries);

        return [
            'success' => false,
            'status' => 0,
            'error' => $errorMessage,
        ];
    }

    /**
     * Asynchronous HTTP request.
     * Returns a Promise resolving to the same standardized array.
     */
    public function requestAsync(string $method, string $url, array $options = [])
    {
        $request = Http::timeout($this->timeout);

        if (!empty($options['headers'])) {
            $request = $request->withHeaders($options['headers']);
        }

        if (!empty($options['query'])) {
            $request = $request->withOptions(['query' => $options['query']]);
        }

        $promise = match (strtoupper($method)) {
            'GET' => $request->async()->get($url),
            'POST' => $request->async()->post($url, $options['json'] ?? []),
            'PUT' => $request->async()->put($url, $options['json'] ?? []),
            'PATCH' => $request->async()->patch($url, $options['json'] ?? []),
            'DELETE' => $request->async()->delete($url, $options['json'] ?? []),
            default => throw new \InvalidArgumentException("HTTP method $method not supported"),
        };

        return $promise->then(
            function (Response $response) use ($method, $url) {
                Log::info("[HttpClient][Async] $method $url Status: {$response->status()}");
                Log::info("[HttpClient][Async] Response: " . $response->body());

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'status' => $response->status(),
                        'data' => $response->json(),
                    ];
                }

                return [
                    'success' => false,
                    'status' => $response->status(),
                    'error' => "[HttpClient] Failed response body: {$response->body()}",
                ];
            },
            function (Throwable $e) use ($method, $url) {
                $errorMsg = "HTTP request exception: " . $e->getMessage();
                Log::error("[HttpClient][Async] $method $url Error: $errorMsg");

                return [
                    'success' => false,
                    'status' => 0,
                    'error' => $errorMsg,
                ];
            }
        );
    }
}