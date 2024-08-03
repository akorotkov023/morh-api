<?php

namespace PlatBox\API\Service\Registry;

use PlatBox\API\Service\Extractor\ExtractorInterface;
use PlatBox\API\Service\Parser\RegistryParserInterface;

/**
 * Class RegistryService
 * @package PlatBox\API\Service\Registry
 */
final class RegistryService
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var RegistryParserInterface
     */
    private $parser;

    /**
     * RegistryService constructor.
     * @param ExtractorInterface $extractor
     * @param RegistryParserInterface $parser
     */
    public function __construct(ExtractorInterface $extractor, RegistryParserInterface $parser)
    {
        $this->extractor = $extractor;
        $this->parser = $parser;
    }

    /**
     * @return void
     */
    public function extract(): void
    {
        // TODO Email extractor does not work. mail2.platbox.com does not accept connections
        $this->extractor->extract('register@3ds.ru', '1qaZXCsw@');
    }

    /**
     * @return RegistryParserInterface
     */
    public function getParser(): RegistryParserInterface
    {
        return $this->parser;
    }
}
