<?php

namespace PlatBox\API\Handler;

use PDOException;
use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\Connection;
use PlatBox\API\Enum\TransactionState;
use PlatBox\API\Exception\FailException;
use PlatBox\API\Exception\RetryException;
use PlatBox\API\Repository\RegistryTransactionRepository;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;
use PlatBox\API\Service\TaskManager\Manager as TaskManager;
use Psr\Log\LoggerInterface;

/**
 * Class TransactionStateHandler
 * @package PlatBox\API\Handler
 */
final class TransactionStateHandler
{
    /**
     * @var ConnectionManagerInterface
     */
    private $connectionManager;

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TransactionStateHandler constructor.
     * @param ConnectionManagerInterface $connectionManager
     * @param TaskManager $taskManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConnectionManagerInterface $connectionManager,
        TaskManager $taskManager,
        LoggerInterface $logger
    ) {
        $this->connectionManager = $connectionManager;
        $this->taskManager = $taskManager;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->logger->info('Searching transaction for processing');
        $connection = $this->connectionManager->getConnection(Connection::API);

        $connection->beginTransaction();

        try {
            $this->run();

            $connection->commit();
        } catch (PDOException $exception) {
            $this->logger->critical($exception->getMessage());
            $connection->rollBack();
        }

        $this->logger->info('Completed');
    }

    /**
     * @return void
     */
    private function run(): void
    {
        $transaction =
            $this
                ->connectionManager
                    ->getRepository(RegistryTransactionRepository::class)
                        ->getQueuedTransaction()
        ;


        if (null === $transaction) {
            $this->logger->info('Transactions was not found. Exiting...');
            return;
        }

        $state = $transaction->getState();

        $this->logger->info('Handling morh transaction with id ' . $transaction->getId());

        try {
            $this->taskManager->run($transaction);

            $this->updateState($transaction, ++$state);
        } catch (RetryException $exception) {
            $this->logger->error('Process will be retrying');

        } catch (FailException $exception) {
            $this->logger->error($exception->getMessage());
            $this->updateState($transaction, TransactionState::FAILED);
        }
    }

    /**
     * @param Transaction $transaction
     * @param int $state
     */
    private function updateState(Transaction $transaction, int $state): void
    {
        $transaction->setState($state);

        $this
            ->connectionManager
                ->getRepository(RegistryTransactionRepository::class)
                    ->update($transaction)
        ;

        $this->logger->info(
            'Transaction ' . $transaction->getId() . ' set state: ' . $transaction->getState()
        );
    }
}
