<?php

namespace PlatBox\API\Service\LongSkiClient;

/**
 * Class Client
 * @package PlatBox\API\Service\LongSkiClient
 */
final class Client implements ClientInterface
{
    /**
     * @inheritDoc
     */
    public function makeRequest(array $data, string $url): array
    {
        $data = (string)json_encode($data);

        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [ $response, $statusCode ];
    }
}
