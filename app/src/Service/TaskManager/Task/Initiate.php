<?php

namespace PlatBox\API\Service\TaskManager\Task;

use PlatBox\API\Entity\Transaction;
use Psr\Log\LoggerInterface;

/**
 * Class Initiate
 * @package PlatBox\API\Service\TaskManager\Task
 */
final class Initiate implements TaskInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Initiate constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function run(Transaction $transaction): void
    {
        $this->logger->info('Initiation step');
    }
}
