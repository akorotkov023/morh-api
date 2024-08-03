<?php

namespace PlatBox\API\Handler;

use PlatBox\API\Service\Registry\RegistryService;


/**
 * Class RegistryObtainHandler
 * @package PlatBox\API\Handler
 */
final class RegistryObtainHandler
{
    /**
     * @var RegistryService
     */
    private $registryService;

    /**
     * RegistryObtainHandler constructor.
     * @param RegistryService $service
     */
    public function __construct(RegistryService $service)
    {
        $this->registryService = $service;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->registryService->extract();
    }
}
