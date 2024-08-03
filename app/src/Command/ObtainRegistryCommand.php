<?php

namespace PlatBox\API\Command;

use InvalidArgumentException;
use PlatBox\API\Enum\Messages;
use PlatBox\API\Handler\RegistryObtainHandler;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ObtainRegistryCommand
 * @package PlatBox\API\Command
 */
final class ObtainRegistryCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RegistryObtainHandler
     */
    private $service;

    /**
     * ObtainRegistryCommand constructor.
     * @param LoggerInterface $logger
     * @param RegistryObtainHandler $service
     */
    public function __construct(LoggerInterface $logger, RegistryObtainHandler $service)
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
        $this->setName('api:registries:obtain');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info(sprintf(Messages::STARTED, self::class));

        $this->service->handle();
    }
}
