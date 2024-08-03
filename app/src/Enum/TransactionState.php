<?php

namespace PlatBox\API\Enum;

/**
 * Class TransactionState
 * @package PlatBox\API\Enum
 */
final class TransactionState
{
    public const IMPORTED                       = 1;

    public const IMPORT_INTO_TRANSACTIONS       = 2;

    public const FINISHED                       = 3;

    public const FAILED                         = 100;
}
