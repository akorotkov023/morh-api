<?php

namespace PlatBox\API\Service\TaskManager\Task;

use PlatBox\API\Entity\Transaction;
use PlatBox\API\Exception\FailException;
use PlatBox\API\Repository\PayBoxTransactionRepository;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ImportIntoPlatBoxDataBase
 * @package PlatBox\API\Service\TaskManager\Task
 */
final class ImportIntoPlatBoxDataBase implements TaskInterface
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
     * ImportIntoPlatBoxDataBase constructor.
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
        $dealData = $this->getDeal($transaction);

        $dealId         = $dealData[0]['id'];
        $productOutName = $dealData[0]['name'];

        $payBoxTransactionId = $this->createPayBoxTransaction($transaction, $dealId, $productOutName);

        $this->logger->info('PayBox transaction: ' . $payBoxTransactionId);

        $transaction->setPlatboxTransactionId($payBoxTransactionId);
    }

    /**
     * @param Transaction $transaction
     * @return array
     */
    private function getDeal(Transaction $transaction): array
    {
        $result =
            $this
                ->manager
                    ->getRepository(PayBoxTransactionRepository::class)
                        ->getDealData($transaction->getProductId())
        ;

        if (0 === count($result)) {
            throw new FailException('Product id was not found in PlatBox database');
        }

        return $result;
    }

    /**
     * @param Transaction $transaction
     * @param int $dealId
     * @param string $productOutName
     * @return int
     */
    private function createPayBoxTransaction(Transaction $transaction, int $dealId, string $productOutName): int
    {
        return
            $this
                ->manager
                    ->getRepository(PayBoxTransactionRepository::class)
                        ->spawn($dealId, $productOutName, $transaction)
        ;
    }
}
