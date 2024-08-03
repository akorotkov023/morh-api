<?php

namespace PlatBox\API\Repository;

use JMS\Serializer\ArrayTransformerInterface;
use PDO;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;

/**
 * Class AbstractRepository
 * @package PlatBox\API\Repository
 */
abstract class AbstractRepository
{
    /**
     * @var ConnectionManagerInterface
     */
    private $connection;

    /**
     * @var ArrayTransformerInterface
     */
    private $serializer;

    /**
     * AbstractRepository constructor.
     * @param ConnectionManagerInterface $connection
     * @param ArrayTransformerInterface $arrayTransformer
     */
    public function __construct(ConnectionManagerInterface $connection, ArrayTransformerInterface $arrayTransformer)
    {
        $this->connection = $connection;
        $this->serializer = $arrayTransformer;
    }

    /**
     * @return string
     */
    abstract protected function getConnectionName(): string;

    /**
     * @return PDO
     */
    protected function getConnection(): PDO
    {
        return $this->connection->getConnection($this->getConnectionName());
    }

    /**
     * @return ArrayTransformerInterface
     */
    protected function getSerializer(): ArrayTransformerInterface
    {
        return $this->serializer;
    }
}
