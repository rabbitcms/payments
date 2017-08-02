<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface TransactionInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface TransactionInterface
{
    const STATUS_PENDING = 0;
    const STATUS_SUCCESSFUL = 1;
    const STATUS_REFUND = 2;

    const STATUS_FAILURE = 127;
    const STATUS_CANCELED = 128;
    const STATUS_UNKNOWN = 255;

    /**
     * @return int
     */
    public function getStatus(): int;
}
