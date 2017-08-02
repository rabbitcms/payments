<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

use DateTimeInterface;

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
     * @return ProductInterface|null
     */
    public function getProduct();

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getCurrency(): string;

    /**
     * @return DateTimeInterface|null
     */
    public function getExpired();

    /**
     * @return string
     */
    public function getLanguage(): string;
}
