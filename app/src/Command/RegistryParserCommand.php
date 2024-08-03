<?php

namespace PlatBox\API\Command;

use InvalidArgumentException;
use PlatBox\API\Enum\Messages;
use PlatBox\API\Handler\RegistryParserHandler;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RegistryParserCommand
 * @package PlatBox\API\Command
 */
final class RegistryParserCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RegistryParserHandler
     */
    private $service;

    /**
     * ObtainRegistryCommand constructor.
     * @param LoggerInterface $logger
     * @param RegistryParserHandler $service
     */
    public function __construct(LoggerInterface $logger, RegistryParserHandler $service)
    {
        $this->logger = $logger;
        $this->service = $service;

        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('api:registries:parse');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info(sprintf(Messages::STARTED, self::class));

        $this->service->handle();

        return 1;
    }
}
