<?php

namespace PlatBox\API\Service\TaskManager;

use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\TransactionState;
use PlatBox\API\Service\TaskManager\Task\ImportIntoPlatBoxDataBase;
use PlatBox\API\Service\TaskManager\Task\Initiate;
use PlatBox\API\Service\TaskManager\Task\TaskInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Manager
 * @package PlatBox\API\Service\TaskManager
 */
final class Manager
{
    /**
     * @var array
     */
    private static $handlersMap = [
        TransactionState::IMPORTED                   => Initiate::class,
        TransactionState::IMPORT_INTO_TRANSACTIONS   => ImportIntoPlatBoxDataBase::class
    ];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * Manager constructor.
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->container = $container;
    }

    /**
     * @param Transaction $transaction
     */
    public function run(Transaction $transaction): void
    {
        /** @var TaskInterface $task */
        $task = $this->container->get(self::$handlersMap[$transaction->getState()]);

        $this->logger->info(get_class($task) . ' has been started for transaction ' . $transaction->getId());

        $task->run($transaction);
    }
}
