<?php

namespace PlatBox\API\Service\TaskManager\Task;

use PlatBox\API\Entity\Transaction;
use PlatBox\API\Exception\RetryException;
use PlatBox\API\Repository\TransactionRevenueRepository;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CheckCommissionUpdateStatus
 * @package PlatBox\API\Service\TaskManager\Task
 */
final class CheckCommissionUpdateStatus implements TaskInterface
{
    /**
     * @var ConnectionManagerInterface
     */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpdateCommission constructor.
     * @param ConnectionManagerInterface $manager
     * @param LoggerInterface $logger
     */
    public function __construct(ConnectionManagerInterface $manager, LoggerInterface $logger)
    {
        $this->manager = $manager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function run(Transaction $transaction): void
    {
        $this->logger->info('Searching for a record in transaction revenue');

        $result =
            $this
                ->manager
                    ->getRepository(TransactionRevenueRepository::class)
                        ->getRevenue($transaction->getPlatboxTransactionId())
        ;

        $this->logger->info('Found ' . count($result) . ' revenue records');

        if (0 === count($result)) {
            throw new RetryException('Revenue records count is 0');
        }

        $this->logger->info('Revenue has been found');
    }
}
