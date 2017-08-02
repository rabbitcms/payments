<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use RabbitCMS\Payments\Contracts\InvoiceInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;

/**
 * Class Invoice
 *
 * @package RabbitCMS\Payments\Support
 */
class Invoice implements InvoiceInterface
{
    /**
     * @var PaymentProviderInterface
     */
    protected $provider;

    /**
     * @var string
     */
    protected $invoice;

    /**
     * @var string
     */
    protected $orderId;

    /**
     * @var float
     */
    protected $amount;

    /**
     * Invoice constructor.
     *
     * @param PaymentProviderInterface $provider
     * @param string                   $invoice
     * @param string                   $orderId
     * @param float                    $amount
     */
    public function __construct(PaymentProviderInterface $provider, string $invoice, string $orderId, float $amount)
    {
        $this->provider = $provider;
        $this->invoice = $invoice;
        $this->orderId = $orderId;
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getInvoice(): string
    {
        return $this->invoice;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface
    {
        return $this->provider;
    }
}
