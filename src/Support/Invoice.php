<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use RabbitCMS\Payments\Contracts\CardTokenInterface;
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
    protected $transactionId;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $status;

    /**
     * @var CardTokenInterface|null
     */
    protected $card;

    /**
     * Invoice constructor.
     *
     * @param PaymentProviderInterface $provider
     * @param string                   $invoice
     * @param string                   $transactionId
     * @param int                      $type
     * @param int                      $status
     * @param float                    $amount
     */
    public function __construct(
        PaymentProviderInterface $provider,
        string $invoice,
        string $transactionId,
        int $type,
        int $status,
        float $amount
    ) {
        $this->provider = $provider;
        $this->invoice = $invoice;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
        $this->type = $type;
        $this->status = $status;
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
    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface
    {
        return $this->provider;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $type
     *
     * @return Invoice
     */
    public function setType(int $type): Invoice
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param int $status
     *
     * @return Invoice
     */
    public function setStatus(int $status): Invoice
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return CardTokenInterface|null
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param null|CardTokenInterface $card
     *
     * @return Invoice
     */
    public function setCard(CardTokenInterface $card): Invoice
    {
        $this->card = $card;
        return $this;
    }
}
