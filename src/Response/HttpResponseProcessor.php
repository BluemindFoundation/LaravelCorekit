<?php

namespace Corekit\Response;

class HttpResponseProcessor
{
    /**
     * Process the HTTP client response.
     *
     * @param array|string|null $response The response from HttpClient::request()
     * @return array|null The 'data' if success, or null on failure.
     */
    public static function processResponse($response): ?array
    {
        if (!is_array($response)) {
            return null;
        }
        if (!empty($response['success']) && $response['success'] === true) {
            return $response['data'] ?? null;
        }
        return null;
    }
}