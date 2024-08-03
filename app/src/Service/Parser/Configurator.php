<?php

namespace PlatBox\API\Service\Parser;

/**
 * Class Configurator
 * @package PlatBox\API\Service\Parser
 */
final class Configurator
{
    /**
     * @var int
     */
    public $transactionId;

    /**
     * @var int
     */
    public $transactionDate;

    /**
     * @var int
     */
    public $clientSign;

    /**
     * @var int
     */
    public $amountWithBankCommission;

    /**
     * @var int
     */
    public $commission;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var int
     */
    public $productId;

    /**
     * @var int
     */
    public $skipLines;

    /**
     * @var string
     */
    public $delimiter;

    /**
     * Configurator constructor.
     * @param int $transactionId
     * @param int $transactionDate
     * @param int $clientSign
     * @param int $amountWithBankCommission
     * @param int $commission
     * @param int $amount
     * @param int $productId
     * @param int $skipLines
     * @param string $delimiter
     */
    public function __construct(
        int $transactionId,
        int $transactionDate,
        int $clientSign,
        int $amountWithBankCommission,
        int $commission,
        int $amount,
        int $productId,
        int $skipLines,
        string $delimiter
    ) {
        $this->transactionId = $transactionId;
        $this->transactionDate = $transactionDate;
        $this->clientSign = $clientSign;
        $this->amountWithBankCommission = $amountWithBankCommission;
        $this->commission = $commission;
        $this->amount = $amount;
        $this->productId = $productId;
        $this->skipLines = $skipLines;
        $this->delimiter = $delimiter;
    }
}
