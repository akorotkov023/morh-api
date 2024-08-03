<?php

namespace PlatBox\API\Handler;

use PDOException;
use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\Connection;
use PlatBox\API\Enum\Messages;
use PlatBox\API\Repository\RegistryTransactionRepository;
use PlatBox\API\Service\DataBaseConnectionManager\ConnectionManagerInterface;
use PlatBox\API\Service\Parser\Configurator;
use PlatBox\API\Service\Registry\RegistryService;
use Psr\Log\LoggerInterface;
use SplFileObject;
use SplObjectStorage;

/**
 * Class RegistryParserHandler
 * @package PlatBox\API\Handler
 */
final class RegistryParserHandler
{
    /**
     * @var RegistryService
     */
    private $registryService;

    /**
     * @var string
     */
    private $inboxFilesDirectory;

    /**
     * @var string
     */
    private $handledFilesDirectory;

    /**
     * @var ConnectionManagerInterface
     */
    private $connectionManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RegistryParserHandler constructor.
     * @param RegistryService $service
     * @param LoggerInterface $logger
     * @param ConnectionManagerInterface $connectionManager
     * @param string $inboxFilesDirectory
     * @param string $handledFilesDirectory
     */
    public function __construct(
        RegistryService $service,
        LoggerInterface $logger,
        ConnectionManagerInterface $connectionManager,
        string $inboxFilesDirectory,
        string $handledFilesDirectory
    ) {
        $this->registryService = $service;
        $this->inboxFilesDirectory = $inboxFilesDirectory;
        $this->logger = $logger;
        $this->handledFilesDirectory = $handledFilesDirectory;
        $this->connectionManager = $connectionManager;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->logger->info(Messages::spawn(Messages::CHECKING_FOR_A_NEW_FILES, $this->inboxFilesDirectory));

        $fileList = preg_grep('/[^\.\..\.*]/', scandir($this->inboxFilesDirectory, SCANDIR_SORT_NONE));

        $this->logger->info(Messages::spawn(Messages::FOUND, count($fileList)));

        $configurator = $this->getConfigurator();

        foreach ($fileList as $fileName) {
            $file = new SplFileObject($this->inboxFilesDirectory . DIRECTORY_SEPARATOR . $fileName, 'r');

            if (!$file->isReadable()) {
                $this->logger->error(
                    Messages::spawn(Messages::ERROR, $file->getPathname(), 'is not readable')
                );

                continue;
            }

            $this->logger->info(Messages::spawn(Messages::PROCESSING, $file->getPathname()));

            try {
                $this->saveTransactions(
                    $this->registryService->getParser()->parse($file, $configurator)
                );
            } catch (PDOException $exception) {
                $this->logger->critical(
                    Messages::spawn(Messages::ERROR, 'PDO', $exception->getMessage())
                );

                $this->logger->notice(Messages::ROLLBACK);

                return;
            }

            rename($file->getPathname(), $this->handledFilesDirectory . DIRECTORY_SEPARATOR . $fileName);
        }
    }

    /**
     * @param SplObjectStorage $storage
     */
    private function saveTransactions(SplObjectStorage $storage): void
    {
        $this->logger->info(Messages::SAVING);

        $connection = $this->connectionManager->getConnection(Connection::API);
        $connection->beginTransaction();

        $this->logger->info(Messages::TRANSACTION_BEGIN);

        $handled = 0;
        foreach ($storage as $transaction) {
            /** @var Transaction $transaction */

            if ($this->isTransactionExists($transaction)) {
                $this->logger->alert(
                    Messages::spawn(
                        Messages::ERROR,
                        $transaction->getTransactionId(),
                        'Transaction already exists'
                    )
                );

                continue;
            }

            $this
                ->connectionManager
                    ->getRepository(RegistryTransactionRepository::class)
                        ->save($transaction)
            ;

            $handled++;
        }

        $connection->commit();

        $this->logger->info(Messages::TRANSACTION_COMMIT);
        $this->logger->info(Messages::spawn(Messages::SAVED, $handled));
    }

    /**
     * @return Configurator
     */
    private function getConfigurator(): Configurator
    {
        return
            new Configurator(
                0,
                1,
                2,
                3,
                4,
                5,
                6,
                7,
                ';'
            );
    }

    /**
     * @param Transaction $transaction
     * @return bool
     */
    private function isTransactionExists(Transaction $transaction): bool
    {
        return
            $this
                ->connectionManager
                    ->getRepository(RegistryTransactionRepository::class)
                        ->getTransactionCountByExternalId($transaction->getTransactionId())
        ;
    }
}
