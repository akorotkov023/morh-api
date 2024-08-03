<?php

namespace PlatBox\API\Service\DataBaseConnectionManager;

use PDO;
use PlatBox\API\Repository\PayBoxTransactionRepository;
use PlatBox\API\Repository\RegistryTransactionRepository;
use PlatBox\API\Repository\TransactionRevenueRepository;

/**
 * Interface ConnectionManagerInterface
 * @package PlatBox\API\Service\DataBaseConnectionManager
 */
interface ConnectionManagerInterface
{
    /**
     * @param string $connectionName
     * @return PDO
     */
    public function getConnection(string $connectionName): PDO;

    /**
     * @param string $repository
     * @return PayBoxTransactionRepository|TransactionRevenueRepository|RegistryTransactionRepository
     */
    public function getRepository(string $repository): PayBoxTransactionRepository|RegistryTransactionRepository|TransactionRevenueRepository;
}
