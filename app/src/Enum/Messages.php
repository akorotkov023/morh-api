<?php

namespace PlatBox\API\Enum;

/**
 * Class Messages
 * @package PlatBox\API\Enum
 */
final class Messages
{
    public const STARTED = '%s has been started';

    public const CHECKING_FOR_A_NEW_FILES = 'Checking for a new files in %s';

    public const FOUND = 'Found %s';

    public const ERROR = 'Subject: %s. Message: %s';

    public const PROCESSING = 'Processing %s';

    public const ROLLBACK = 'Rollback';

    public const TRANSACTION_BEGIN = 'Transaction begin';

    public const TRANSACTION_COMMIT = 'Transaction commit';

    public const SAVING = 'Saving';

    public const SAVED = '%s saved';

    public const CLASS_IS_NOT_INSTANCE = 'Class %s is not instance of %s';

    public const INDEX_VALUE = 'Index: %s value: %s';

    /**
     * @param string $message
     * @param mixed ...$values
     * @return string
     */
    public static function spawn(string $message, ...$values): string
    {
        return sprintf($message, ...$values);
    }
}
