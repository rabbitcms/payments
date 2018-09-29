<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface TransactionInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface TransactionInterface extends InvoiceInterface
{
    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface;

    /**
     * Get transaction options.
     *
     * @return array
     */
    public function getOptions(): array;
}
