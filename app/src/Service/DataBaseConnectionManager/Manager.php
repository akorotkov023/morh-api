<?php

namespace PlatBox\API\Service\DataBaseConnectionManager;

use PDO;
use PlatBox\API\Enum\Messages;
use PlatBox\API\Repository\AbstractRepository;
use PlatBox\API\Repository\RegistryTransactionRepository;
use PlatBox\API\Repository\TransactionRevenueRepository;
use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Class Manager
 * @package PlatBox\API\Service\DataBaseConnectionManager
 */
final class Manager implements ConnectionManagerInterface
{
    /**
     * @var Pool
     */
    private $connectionPool;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Manager constructor.
     * @param Pool $connectionPool
     * @param ContainerInterface $container
     */
    public function __construct(Pool $connectionPool, ContainerInterface $container)
    {
        $this->connectionPool = $connectionPool;
        $this->container = $container;
    }

    /**
     * @param string $connectionName
     * @return PDO
     */
    public function getConnection(string $connectionName): PDO
    {
        return $this->connectionPool->getConnection($connectionName);
    }

    /**
     * @param string $repository
     * @return TransactionRevenueRepository|RegistryTransactionRepository
     */
    public function getRepository(string $repository): object
    {
        if (is_subclass_of($repository, AbstractRepository::class)) {
            return $this->container->get($repository);
        }

        throw new RuntimeException(
                Messages::spawn(Messages::CLASS_IS_NOT_INSTANCE, $repository, AbstractRepository::class)
            )
        ;
    }
}
