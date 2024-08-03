<?php

namespace PlatBox\API\Repository;

use PDO;
use PlatBox\API\Entity\Transaction;
use PlatBox\API\Enum\Connection;

/**
 * Class PayBoxTransactionRepository
 * @package PlatBox\API\Repository
 */
class PayBoxTransactionRepository extends AbstractRepository
{
    /**
     * @param int $dealId
     * @param string $productOutName
     * @param Transaction $transaction
     * @return int
     */
    public function spawn(int $dealId, string $productOutName, Transaction $transaction): int
    {
        $query = '
            INSERT INTO public.transactions(deal_id, merchant_id, provider_id, payer_id, payer_ip_addr, status, payment_amount,
                total_amount, product_out_name, provider_tx_id, provider_tx_datetime, merchant_tx_id,
                merchant_callback_url, merchant_additional, merchant_comment, currency_rate,
                synced_at_bank,
                created_at, updated_at, provider_amount, error_code, error_description,
                provider_parameters,
                mongo_sync_at, merchant_amount)
                VALUES (:dealId, 552, 5, 56936564, NULL, \'success\', :amountPayment, :amountTotal, :productOutName, :providerTransactionId, now(),
                :providerTransactionId, null, null, null, 1, now(), now(), now(), 840, 0, \'Транзакция успешна\', NULL, now(), :merchantAmount);
        ';

        $minorAmount = (int)($transaction->getAmountWithBankCommission() * 100);

        $query = $this->getConnection()->prepare($query);
        $query->bindValue(':amountPayment', $minorAmount, PDO::PARAM_INT);
        $query->bindValue(':amountTotal', $minorAmount, PDO::PARAM_INT);
        $query->bindValue(':merchantAmount', $minorAmount, PDO::PARAM_INT);
        $query->bindValue(':productOutName', $productOutName, PDO::PARAM_STR);
        $query->bindValue(':dealId', $dealId);
        $query->bindValue(':providerTransactionId', $transaction->getTransactionId(), PDO::PARAM_STR);

        $query->execute();

        return (int)$this->getConnection()->lastInsertId();
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getDealData(int $productId): array
    {
        $query = "
            SELECT d.id, po.name
                FROM public.deal d
                    JOIN public.product_in pi ON d.product_in_id = pi.id
                    JOIN public.product_out po ON d.product_out_id = po.id
                WHERE pi.provider_params::jsonb ->> 'ServiceNumber' = :productId and is_active = true
                ORDER BY d.id desc; 
            ";

        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':productId', $productId);
        $statement->execute();

        /** @var array */
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @inheritDoc
     */
    protected function getConnectionName(): string
    {
        return Connection::PLATBOX_GATE_WAY;
    }
}
