<?php

namespace PlatBox\API\Service\LongSkiClient;

/**
 * Interface ClientInterface
 * @package PlatBox\API\Service\LongSkiClient
 */
interface ClientInterface
{
    /**
     * @param array $data
     * @param string $url
     * @return array
     */
    public function makeRequest(array $data, string $url): array;
}
