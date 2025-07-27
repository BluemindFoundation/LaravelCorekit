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

    public function __construct(int $timeout = 5, int $maxRetries = 3)
    {
        $this->timeout = $timeout;
        $this->maxRetries = $maxRetries;
    }

    /**
     * Make a synchronous HTTP request.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array{status: int|null, data: mixed, error: string|null}
     */
    public function request(string $method, string $url, array $options = []): array
    {
        $attempt = 0;
        $errorMessage = null;
        $status = null;
        $data = null;

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

                $status = $response->status();
                $data = $response->json();
                $errorMessage = $response->successful() ? null : $response->body();

                // Logging
                Log::info("[HttpClient] Attempt #$attempt - $method $url");
                Log::info("[HttpClient] Status: $status");
                Log::info("[HttpClient] Body: " . $response->body());

                // On success or client error (stop retrying if 4xx)
                if ($response->successful() || $response->status() < 500) {
                    break;
                }
            } catch (RequestException | Throwable $e) {
                $errorMessage = $e->getMessage();
                Log::error("[HttpClient] Exception on attempt #$attempt to $url: $errorMessage");
            }

            sleep($attempt ** 2); // exponential backoff

        } while ($attempt < $this->maxRetries);

        return [
            'status' => $status,
            'data' => $data,
            'error' => $errorMessage,
        ];
    }

    /**
     * Make an asynchronous HTTP request (not awaited).
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

        return match (strtoupper($method)) {
            'GET' => $request->async()->get($url),
            'POST' => $request->async()->post($url, $options['json'] ?? []),
            'PUT' => $request->async()->put($url, $options['json'] ?? []),
            'PATCH' => $request->async()->patch($url, $options['json'] ?? []),
            'DELETE' => $request->async()->delete($url, $options['json'] ?? []),
            default => throw new \InvalidArgumentException("HTTP method $method not supported"),
        };
    }
}