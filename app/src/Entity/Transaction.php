<?php

namespace PlatBox\API\Entity;

use DateTime;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Transaction
 * @package PlatBox\API\Entity
 */
final class Transaction
{
    /**
     * @var int
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("integer")
     */
    private $id;

    /**
     * @var int
     *
     * @JMS\SerializedName("external_transaction_id")
     * @JMS\Type("integer")
     */
    private $transactionId;

    /**
     * @var DateTime
     *
     * @JMS\SerializedName("created_at")
     * @JMS\Type("DateTime<'Y-m-d H:i:sT'>")
     */
    private $transactionDate;

    /**
     * @var string
     *
     * @JMS\SerializedName("client_sign")
     * @JMS\Type("string")
     */
    private $clientSign;

    /**
     * @var float
     *
     * @JMS\SerializedName("amount_with_bank_commission")
     * @JMS\Type("float")
     */
    private $amountWithBankCommission;

    /**
     * @var float
     *
     * @JMS\SerializedName("commission")
     * @JMS\Type("float")
     */
    private $commission;

    /**
     * @var float
     *
     * @JMS\SerializedName("amount")
     * @JMS\Type("float")
     */
    private $amount;

    /**
     * @var int
     *
     * @JMS\SerializedName("product_id")
     * @JMS\Type("integer")
     */
    private $productId;

    /**
     * @var int|null
     *
     * @JMS\SerializedName("platbox_transaction")
     * @JMS\Type("integer")
     */
    private $platboxTransactionId;

    /**
     * @var int
     *
     * @JMS\SerializedName("state")
     * @JMS\Type("integer")
     */
    private $state;

    /**
     * Transaction constructor.
     * @param int $transactionId
     * @param DateTime $transactionDate
     * @param string $clientSign
     * @param float $amountWithBankCommission
     * @param float $commission
     * @param float $amount
     * @param int $productId
     */
    public function __construct(
        int $transactionId,
        DateTime $transactionDate,
        string $clientSign,
        float $amountWithBankCommission,
        float $commission,
        float $amount,
        int $productId
    ) {
        $this->transactionId = $transactionId;
        $this->transactionDate = $transactionDate;
        $this->clientSign = $clientSign;
        $this->amountWithBankCommission = $amountWithBankCommission;
        $this->commission = $commission;
        $this->amount = $amount;
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTransactionId(): int
    {
        return $this->transactionId;
    }

    /**
     * @return DateTime
     */
    public function getTransactionDate(): DateTime
    {
        return $this->transactionDate;
    }

    /**
     * @return string
     */
    public function getClientSign(): string
    {
        return $this->clientSign;
    }

    /**
     * @return float
     */
    public function getAmountWithBankCommission(): float
    {
        return $this->amountWithBankCommission;
    }

    /**
     * @return float
     */
    public function getCommission(): float
    {
        return $this->commission;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return int|null
     */
    public function getPlatboxTransactionId(): ?int
    {
        return $this->platboxTransactionId;
    }

    /**
     * @param int $platboxTransactionId
     * @return Transaction
     */
    public function setPlatboxTransactionId(int $platboxTransactionId): Transaction
    {
        $this->platboxTransactionId = $platboxTransactionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return Transaction
     */
    public function setState(int $state): Transaction
    {
        $this->state = $state;
        return $this;
    }
}
