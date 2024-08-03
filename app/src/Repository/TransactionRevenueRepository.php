<?php

namespace PlatBox\API\Repository;

use PDO;
use PlatBox\API\Enum\Connection;

/**
 * Class TransactionRevenueRepository
 * @package PlatBox\API\Repository
 */
class TransactionRevenueRepository extends AbstractRepository
{
    /**
     * @param int $transactionId
     * @return array
     */
    public function getRevenue(int $transactionId): array
    {
        $query = 'SELECT * FROM "public"."transaction_revenue" WHERE transaction_id = :id';

        $statement = $this->getConnection()->prepare($query);
        $statement->bindValue(':id', $transactionId);
        $statement->execute();

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
