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

    public function request(string $method, string $url, array $options = []): ?array
    {
        $attempt = 0;

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

                // 🪵 Log détaillé
                Log::info("[HttpClient] Attempt #$attempt - URL: $url");
                Log::info("[HttpClient] Status: {$response->status()}");
                Log::info("[HttpClient] Response Body: " . $response->body());

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning("HTTP request to $url failed with status {$response->status()}");
            } catch (RequestException | Throwable $e) {
                Log::error("HTTP request exception to $url: " . $e->getMessage());
            }

            sleep($attempt ** 2); // retry delay
        } while ($attempt < $this->maxRetries);

        return null;
    }


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