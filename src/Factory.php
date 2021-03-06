<?php

declare(strict_types=1);

namespace RabbitCMS\Payments;

use Illuminate\Support\Manager;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use RabbitCMS\Payments\Contracts\InvoiceInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;
use RabbitCMS\Payments\Entities\CardToken;
use RabbitCMS\Payments\Entities\Transaction;
use InvalidArgumentException;
use DateTime;

/**
 * Class Factory
 *
 * @method PaymentProviderInterface driver($driver = null)
 */
class Factory extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return array_keys($this->config->get('payments', []));
    }

    /**
     * @return array<string,PaymentProviderInterface>
     */
    public function getProviders(): array
    {
        return array_map(fn(callable $creator) => $creator($this, []), $this->customCreators);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        $config = $this->config->get("payments.{$driver}", null);

        if (is_string($config)) {
            return $this->createDriver($config);
        }

        if ($config === null) {
            throw new InvalidArgumentException("Driver [$driver] not supported.");
        }
        $provider = $config['provider'] ?? null;

        // We'll check to see if a creator method exists for the given driver. If not we
        // will check for a custom driver creator, which allows developers to create
        // drivers using their own customized driver creator Closure to create it.
        if (isset($this->customCreators[$provider])) {
            $config['shop'] = $driver;

            return tap(
                $this->callCustomCreator($provider, $config),
                function (PaymentProviderInterface $provider) use ($driver) {
                    $provider->setLogger(
                        new Logger($driver, [new RotatingFileHandler(storage_path("logs/{$driver}.log"))])
                    );
                }
            );
        }
        throw new InvalidArgumentException("Provider [$provider] not supported.");
    }

    public function callCustomCreator($provider, array $config = []): PaymentProviderInterface
    {
        return $this->customCreators[$provider]($this, $config);
    }

    public function process(InvoiceInterface $invoice): void
    {
        /* @var Transaction $transaction */
        $transaction = Transaction::query()
            ->where('driver', $invoice->getProvider()->getShop())
            ->whereNull('parent_id')
            ->findOrFail($invoice->getTransactionId());

        $transaction->getConnection()->transaction(function () use ($invoice, $transaction) {
            switch ($invoice->getStatus()) {
                case InvoiceInterface::STATUS_FAILURE:
                    if ($transaction->type === Transaction::TYPE_SUBSCRIPTION) {
                        if ($transaction->children()->where('invoice', $invoice->getInvoice())->exists()) {
                            return;
                        }
                        $trans = $transaction->replicate();
                        $trans->parent()->associate($transaction);
                        $trans->fill([
                            'driver' => $transaction->driver,
                            'type' => Transaction::TYPE_PAYMENT,
                            'status' => Transaction::STATUS_FAILURE,
                            'amount' => $invoice->getAmount(),
                            'commission' => $invoice->getCommission(),
                            'invoice' => $invoice->getInvoice(),
                            'processed_at' => new DateTime('now'),
                        ]);
                        $trans->save();
                        $transaction = $trans;
                        break;
                    }
                    if ($transaction->status === Transaction::STATUS_FAILURE) {
                        return;
                    }
                    $transaction->update([
                        //'error' => $params['err_description'],
                        'status' => Transaction::STATUS_FAILURE,
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now'),
                    ]);

                    break;
                case InvoiceInterface::STATUS_CANCELED:
                    if ($transaction->type === Transaction::TYPE_SUBSCRIPTION) {
                        $transaction->update([
                            'status' => Transaction::STATUS_CANCELED,
                            'invoice' => $invoice->getInvoice(),
                            'processed_at' => new DateTime('now'),
                            'amount' => 0,
                            'commission' => 0,
                        ]);
                        break;
                    }

                    return;
                case InvoiceInterface::STATUS_SUCCESSFUL:
                    if ($transaction->type === Transaction::TYPE_SUBSCRIPTION) {
                        if ($invoice->getType() === Transaction::TYPE_SUBSCRIPTION) {
                            if ($transaction->status === Transaction::STATUS_SUCCESSFUL) {
                                return;
                            }
                            $transaction->update([
                                'status' => Transaction::STATUS_SUCCESSFUL,
                                'invoice' => $invoice->getInvoice(),
                                'processed_at' => new DateTime('now'),
                                'amount' => 0,
                                'commission' => 0,
                            ]);
                            break;
                        }

                        if ($transaction->children()->where('invoice', $invoice->getInvoice())->exists()) {
                            return;
                        }

                        $trans = $transaction->replicate();
                        $trans->parent()->associate($transaction);
                        $trans->fill([
                            'driver' => $transaction->driver,
                            'type' => Transaction::TYPE_PAYMENT,
                            'status' => Transaction::STATUS_SUCCESSFUL,
                            'amount' => $invoice->getAmount(),
                            'commission' => $invoice->getCommission(),
                            'invoice' => $invoice->getInvoice(),
                            'processed_at' => new DateTime('now'),
                        ]);
                        $trans->save();
                        $transaction = $trans;

//                        $trans = Transaction::query()->where(['invoice' => $params['payment_id']])->first();
//                        if ($trans !== null) {
//                            //Already exists.
//                            return;
//                        }
//                        $trans = new Transaction();
//                        $trans->subscribe()->associate($transaction);
//                        $trans->order()->associate($transaction->order);
//                        $trans->fill([
//                            'type' => Transaction::TYPE_PAYMENT,
//                            'status' => Transaction::STATUS_SUCCESSFUL,
//                            'invoice' => $params['payment_id'],
//                            'result_at' => new DateTime('now')
//                        ]);
//                        $trans->save();
//                        $transaction = $trans;
                        break;
                    }
                    if ($transaction->status === Transaction::STATUS_SUCCESSFUL) {
                        return;
                    }

                    $card = $invoice->getCard();
                    if ($card) {
                        $newCard = new CardToken([
                            'card' => $card->getCard(),
                            'token' => $card->getToken(),
                            'data' => $card->getData(),
                            'client' => $transaction->client,
                            'driver' => $transaction->driver,
                        ]);
                        $newCard->save();
                        $transaction->card()->associate($newCard);
                    }

                    $transaction->update([
                        'status' => Transaction::STATUS_SUCCESSFUL,
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now'),
                        'commission' => $invoice->getCommission(),
                    ]);
                    break;
                case InvoiceInterface::STATUS_REFUND:
                    if ($transaction->status === Transaction::STATUS_REFUND) {
                        return;
                    }
                    $trans = $transaction->replicate();
                    $trans->parent()->associate($transaction);
                    $trans->fill([
                        'driver' => $transaction->driver,
                        'type' => Transaction::TYPE_REFUND,
                        'status' => Transaction::STATUS_REFUND,
                        'amount' => -$invoice->getAmount(),
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now'),
                    ]);
                    $trans->save();
                    $transaction->update([
                        'status' => Transaction::STATUS_REFUND,
                    ]);
                    $transaction = $trans;
                    break;
//                case 'subscribed':
//                    if ($transaction->status === Transaction::STATUS_SUBSCRIBED) {
//                        return;
//                    }
//                    $transaction->update([
//                        'status' => Transaction::STATUS_SUBSCRIBED,
//                        'invoice' => $params['payment_id'],
//                        'result_at' => new DateTime('now')
//                    ]);
//                    break;
//                case 'unsubscribed':
//                    if ($transaction->status === Transaction::STATUS_UNSUBSCRIBED) {
//                        return;
//                    }
//                    $transaction->update([
//                        'status' => Transaction::STATUS_UNSUBSCRIBED
//                    ]);
//                    $trans = new Transaction();
//                    $trans->subscribe()->associate($transaction);
//                    $trans->order()->associate($transaction->order);
//                    $trans->fill([
//                        'type' => Transaction::TYPE_UNSUBSCRIBE,
//                        'status' => Transaction::STATUS_UNSUBSCRIBED,
//                        'invoice' => $params['payment_id'],
//                        'result_at' => new DateTime('now')
//                    ]);
//                    $trans->save();
//                    $transaction = $trans;
//                    break;
                default:
                    $transaction->update([
                        'status' => Transaction::STATUS_UNKNOWN,
                        //'options' => $params
                    ]);
            }

            $transaction->order->paymentStatus($transaction);
        });
    }
}
