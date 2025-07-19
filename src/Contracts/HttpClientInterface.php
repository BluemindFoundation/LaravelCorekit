<?php

namespace Corekit\Contracts;

interface HttpClientInterface
{
    /**
     * Synchronous HTTP request.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array|null
     */
    public function request(string $method, string $url, array $options = []): ?array;

    /**
     * Asynchronous HTTP request returning a promise.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public function requestAsync(string $method, string $url, array $options = []);
}