<?php

namespace PlatBox\API\Command;

use InvalidArgumentException;
use PlatBox\API\Enum\Messages;
use PlatBox\API\Service\Inspect\InspectService;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InspectConfig
 * @package PlatBox\API\Command
 */
final class InspectConfig extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var InspectService
     */
    private $service;

    /**
     * ObtainRegistryCommand constructor.
     * @param LoggerInterface $logger
     * @param InspectService $service
     */
    public function __construct(LoggerInterface $logger, InspectService $service)
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
        $this->setName('api:config:inspect');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws RuntimeException
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info(sprintf(Messages::STARTED, self::class));

        $this->service->run();

        return 1;
    }
}
