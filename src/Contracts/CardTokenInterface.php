<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Interface CardTokenInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface CardTokenInterface
{
    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface;

    /**
     * Get client identifier.
     *
     * @return string
     */
    public function getClientId(): string;

    /**
     * @return string
     */
    public function getCard(): string;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return array
     */
    public function getData(): array;
}
