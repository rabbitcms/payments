<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Support;

use DateTimeInterface;
use RabbitCMS\Payments\Contracts\ClientInterface;
use RabbitCMS\Payments\Contracts\PaymentInterface;
use RabbitCMS\Payments\Contracts\ProductInterface;

/**
 * Class Payment
 *
 * @package RabbitCMS\Payments\Support
 */
class Payment implements PaymentInterface
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ProductInterface|null
     */
    protected $product;

    /**
     * @var DateTimeInterface|null
     */
    protected $expired;

    /**
     * @var string
     */
    protected $language = '';

    /**
     * @var string
     */
    protected $returnUrl = '';

    /**
     * @var int|null
     */
    protected $cardId;

    /**
     * Payment constructor.
     *
     * @param  string  $currency
     * @param  float  $amount
     * @param  string  $description
     * @param  Client  $client
     */
    public function __construct(string $currency, float $amount, string $description, Client $client)
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->description = $description;
        $this->client = $client;
    }

    /**
     * Get client.
     *
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * Get amount.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct()
    {
        return $this->product;
    }


    /**
     * @return DateTimeInterface|null
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param  ProductInterface  $product
     *
     * @return Payment
     */
    public function setProduct(ProductInterface $product): Payment
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param  DateTimeInterface  $expired
     *
     * @return Payment
     */
    public function setExpired(DateTimeInterface $expired): Payment
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * @param  string  $language
     *
     * @return Payment
     */
    public function setLanguage(string $language): Payment
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @param  string  $returnUrl
     *
     * @return Payment
     */
    public function setReturnUrl(string $returnUrl): Payment
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * Get card id for payment.
     *
     * @return int|null
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * @param  int  $cardId
     *
     * @return Payment
     */
    public function setCardId(int $cardId): Payment
    {
        $this->cardId = $cardId;

        return $this;
    }
}
