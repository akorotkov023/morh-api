<?php

namespace PlatBox\API\Service\Parser;

use DateTime;
use Exception;
use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\Messages;
use Psr\Log\LoggerInterface;
use SplFileObject;
use SplObjectStorage;

/**
 * Class RegistryParser
 * @package PlatBox\API\Service\Parser
 */
final class RegistryParser implements RegistryParserInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RegistryParser constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param SplFileObject $file
     * @param Configurator $configurator
     * @return SplObjectStorage[Transaction]
     * @throws Exception
     */
    public function parse(SplFileObject $file, Configurator $configurator): SplObjectStorage
    {
        $file->setFlags(
            SplFileObject::READ_CSV
            | SplFileObject::DROP_NEW_LINE
            | SplFileObject::SKIP_EMPTY
            | SplFileObject::READ_AHEAD
        );

        $file->setCsvControl($configurator->delimiter);

        $storage = new SplObjectStorage();
        $lines = 0;

        $this->logger->info('Headers');

        $i = 0;
        /** @var iterable $headers */
        $headers = $file->fgetcsv();
        foreach ($headers as $header) {
            ++$i;

            $header = iconv('WINDOWS-1251', 'UTF-8', $header);
            $this->logger->info(Messages::spawn(Messages::INDEX_VALUE, $i, $header));
        }
        //Запись в хранилище
        while (!$file->eof()) {
            $transactionData = $file->fgetcsv();

            if (null === $transactionData || empty($transactionData)) {
                continue;
            }

            $storage->attach(
                new Transaction(
                    $transactionData[$configurator->transactionId],
                    new DateTime($transactionData[$configurator->transactionDate]),
                    $transactionData[$configurator->clientSign],
                    $this->toFloat($transactionData[$configurator->amountWithBankCommission]),
                    $this->toFloat($transactionData[$configurator->commission]),
                    $this->toFloat($transactionData[$configurator->amount]),
                    $transactionData[$configurator->productId]
                )
            );

            $lines++;
        }

        $this->logger->info('Handled ' . $lines . ' lines');

        unset($file);

        return $storage;
    }

    /**
     * @param string $value
     * @return float
     */
    private function toFloat(string $value): float
    {
        return (float)str_replace(',', '.', $value);
    }
}
