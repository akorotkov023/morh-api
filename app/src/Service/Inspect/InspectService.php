<?php

namespace PlatBox\API\Service\Inspect;

use PlatBox\API\Enum\Connection;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class InspectService
 * @package PlatBox\API\Service
 */
final class InspectService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ConnectionManagerInterface
     */
    private $connectionManager;

    /**
     * @var array
     */
    private $connectionList;

    /**
     * InspectService constructor.
     * @param LoggerInterface $logger
     * @param ConnectionManagerInterface $manager
     * @param array $connectionData
     */
    public function __construct(LoggerInterface $logger, ConnectionManagerInterface $manager, array $connectionData = [])
    {
        $this->logger = $logger;
        $this->connectionManager = $manager;
        $this->connectionList = $connectionData;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->logger->info('Inspect connection with: ' . Connection::API);
        $this->inspectConnection(Connection::API);

        $this->logger->info('Inspect connection with: ' . Connection::PLATBOX_GATE_WAY);
        $this->inspectConnection(Connection::PLATBOX_GATE_WAY);
    }

    /**
     * @param string $connectionName
     */
    private function inspectConnection(string $connectionName): void
    {
        $this->logger->info('Connection data', $this->connectionList[$connectionName]);

        $connection = $this->connectionManager->getConnection($connectionName);

        $statement = $connection->prepare('SELECT VERSION() as version');

        $result = $statement->execute();

        if ($result) {
            $this->logger->info('Connection successfully');

            $this->logger->info('Postgres version', [$statement->fetchColumn(0)]);
        } else {
            $this->logger->error('Connection error');
        }
    }
}
