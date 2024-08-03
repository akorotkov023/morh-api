<?php

namespace PlatBox\API\Service\Parser;

use InvalidArgumentException;
use SplFileObject;
use SplObjectStorage;

/**
 * Interface RegistryParserInterface
 * @package PlatBox\API\Service\Parser
 */
interface RegistryParserInterface
{
    /**
     * @param SplFileObject $fileObject
     * @param Configurator $configurator
     * @throws InvalidArgumentException
     *
     * @return SplObjectStorage
     */
    public function parse(SplFileObject $fileObject, Configurator $configurator): SplObjectStorage;
}
