<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Contracts;

/**
 * Class InvoiceInterface
 *
 * @package RabbitCMS\Payments\Contracts
 */
interface InvoiceInterface
{
    /**
     * @return string
     */
    public function getInvoice(): string;

    /**
     * @return float
     */
    public function getAmount(): float;

    /**
     * @return string
     */
    public function getOrderId(): string;

    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface;
}
