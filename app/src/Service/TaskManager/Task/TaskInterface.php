<?php

namespace PlatBox\API\Service\TaskManager\Task;

use PlatBox\API\Entity\Transaction;

/**
 * Interface TaskInterface
 * @package PlatBox\API\Service\TaskManager\Task
 */
interface TaskInterface
{
    /**
     * @param Transaction $transaction
     */
    public function run(Transaction $transaction): void;
}
