<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface PaymentInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface PaymentInterface
{
    /**
     * Get client.
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount(): float;

}
