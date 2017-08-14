<?php
declare(strict_types=1);
namespace RabbitCMS\Payments\Contracts;

/**
 * Interface ContinuableInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface ContinuableInterface
{
    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface;
}
