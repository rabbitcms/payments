<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Concerns;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use RabbitCMS\Payments\Contracts\OrderInterface;
use RabbitCMS\Payments\Contracts\PaymentInterface;
use RabbitCMS\Payments\Contracts\SubscribeOrderInterface;
use RabbitCMS\Payments\Contracts\TransactionInterface;
use RabbitCMS\Payments\Entities\Transaction;
use RabbitCMS\Payments\Factory;

/**
 * Trait PaymentProvider
 *
 * @package RabbitCMS\Payments\Concerns
 */
trait PaymentProvider
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var Container
     */
    protected $manager;

    /**
     * PaymentProvider constructor.
     *
     * @param  Factory  $manager
     * @param  array  $config
     */
    public function __construct(Factory $manager, array $config)
    {
        $this->manager = $manager;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getShop(): string
    {
        return $this->config('shop');
    }

    /**
     * @param  string  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    protected function config(string $key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    /**
     * @return string
     */
    protected function getCallbackUrl(): string
    {
        return URL::route('payments.callback', ['shop' => $this->getShop()]);
    }

    /**
     * @param  OrderInterface  $order
     * @param  PaymentInterface  $payment
     * @param  array  $options
     * @param  bool  $subscription
     * @return TransactionInterface
     */
    protected function makeTransaction(OrderInterface $order, PaymentInterface $payment, array $options = [], bool $subscription = false): TransactionInterface
    {
        $transaction = new Transaction([
            'type' => $subscription ? Transaction::TYPE_SUBSCRIPTION : Transaction::TYPE_PAYMENT,
            'status' => Transaction::STATUS_PENDING,
            'amount' => $payment->getAmount(),
            'driver' => $this->getShop(),
            'client' => $payment->getClient()->getId(),
            'options' => $options,
        ]);

        $transaction->order()->associate($order);

        $transaction->save();

        return $transaction;
    }
}
