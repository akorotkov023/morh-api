<?php

namespace PlatBox\API\Repository;

use PDO;
use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\Connection;
use PlatBox\API\Enum\TransactionState;

/**
 * Class RegistryTransactionRepository
 * @package PlatBox\API\Repository
 */
class RegistryTransactionRepository extends AbstractRepository
{
    /**
     * @return Transaction|null
     */
    public function getQueuedTransaction(): ?Transaction
    {
        $query = 'SELECT * FROM public.registries_transactions WHERE state < :state LIMIT 1 FOR UPDATE SKIP LOCKED';

        $statement = $this->getConnection()->prepare($query);

        $statement->bindValue(':state', TransactionState::FINISHED);
        $statement->execute();

        /** @var array $result */
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (0 === count($result)) {
            return null;
        }

        return $this->getSerializer()->fromArray($result[0], Transaction::class);
    }

    /**
     * @param int $externalId
     * @return bool
     */
    public function getTransactionCountByExternalId(int $externalId)
    {
        $query = 'SELECT 1 FROM public.registries_transactions WHERE external_transaction_id = ?';

        $statement = $this->getConnection()->prepare($query);
        $statement->execute([$externalId]);

        return 0 < $statement->rowCount();
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function update(Transaction $transaction): void
    {
        $query = '
            UPDATE public.registries_transactions
                SET
                    "created_at" = ?,
                    "client_sign" = ? ,
                    "amount_with_bank_commission" = ?,
                    "commission" = ?,
                    "amount" = ?,
                    "product_id" = ?,
                    "external_transaction_id" = ?,
                    "state" = ?,
                    "platbox_transaction" = ?
            WHERE id = ?
        ';

        $this->getConnection()->prepare($query)->execute([
            $transaction->getTransactionDate()->format(DATE_ATOM),
            $transaction->getClientSign(),
            $transaction->getAmountWithBankCommission(),
            $transaction->getCommission(),
            $transaction->getAmount(),
            $transaction->getProductId(),
            $transaction->getTransactionId(),
            $transaction->getState(),
            $transaction->getPlatboxTransactionId(),
            $transaction->getId()
        ]);
    }

    /**
     * @param Transaction $transaction
     * @return void
     */
    public function save(Transaction $transaction): void
    {
        $query = '
            INSERT INTO public.registries_transactions
                (
                    "created_at",
                    "client_sign",
                    "amount_with_bank_commission",
                    "commission",
                    "amount",
                    "product_id",
                    "external_transaction_id",
                    "state"
                )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        ;

        $this->getConnection()->prepare($query)->execute([
            $transaction->getTransactionDate()->format(DATE_ATOM),
            $transaction->getClientSign(),
            $transaction->getAmountWithBankCommission(),
            $transaction->getCommission(),
            $transaction->getAmount(),
            $transaction->getProductId(),
            $transaction->getTransactionId(),
            TransactionState::IMPORTED
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getConnectionName(): string
    {
        return Connection::API;
    }
}
