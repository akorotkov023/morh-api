<?php

namespace PlatBox\API\Service\Extractor;

/**
 * Interface ExtractorInterface
 * @package PlatBox\API\Service\Extractor
 */
interface ExtractorInterface
{
    /**
     * @param string $login
     * @param string $password
     */
    public function extract(string $login, string $password): void;
}
