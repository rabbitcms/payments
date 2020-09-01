<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Class InvoiceInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface InvoiceInterface extends ContinuableInterface
{
    public const TYPE_PAYMENT = 1;
    public const TYPE_REFUND = 2;
    public const TYPE_SUBSCRIPTION = 3;

    public const STATUS_PENDING = 0;
    public const STATUS_SUCCESSFUL = 1;
    public const STATUS_REFUND = 2;

    public const STATUS_FAILURE = 127;
    public const STATUS_CANCELED = 128;
    public const STATUS_UNKNOWN = 255;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @return string
     */
    public function getInvoice(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return float
     */
    public function getCommission(): float;

    /**
     * @return string
     */
    public function getTransactionId(): string;

    /**
     * @return int
     */
    public function getStatus(): int;

    /**
     * @return CardTokenInterface|null
     */
    public function getCard();
}
