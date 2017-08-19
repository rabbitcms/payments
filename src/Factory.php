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
 * @package DtKt\Payments
 * @method PaymentProviderInterface driver($driver = null)
 */
class Factory extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return 'default';
    }

    /**
     * @return string[]
     */
    public function all():array
    {
        return array_keys($this->customCreators);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string $driver
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createDriver($driver)
    {
        $config = config("payments.{$driver}", []);
        if (is_string($config)) {
            return $this->createDriver($config);
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
        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param string $provider
     * @param array  $config
     *
     * @return mixed
     */
    protected function callCustomCreator($provider, array $config = [])
    {
        return $this->customCreators[$provider]($this, $config);
    }

    /**
     * @param InvoiceInterface $invoice
     */
    public function process(InvoiceInterface $invoice)
    {
        /* @var Transaction $transaction */
        $transaction = Transaction::query()
            ->where('driver', $invoice->getProvider()->getShop())
            ->findOrFail($invoice->getTransactionId());

        $transaction->getConnection()->transaction(function () use ($invoice, $transaction) {
            switch ($invoice->getStatus()) {
                case 'failure':
                    if ($transaction->status === Transaction::STATUS_FAILURE) {
                        return;
                    }
                    $transaction->update([
                        //'error' => $params['err_description'],
                        'status' => Transaction::STATUS_FAILURE,
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now')
                    ]);

                    break;
                case InvoiceInterface::STATUS_SUCCESSFUL:
//                    if ($transaction->type === Transaction::TYPE_SUBSCRIBE) {
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
//                        break;
//                    }
                    if ($transaction->status === Transaction::STATUS_SUCCESSFUL) {
                        return;
                    }

                    $card = $invoice->getCard();
                    if ($card) {
                        $newCard = new CardToken([
                            'card'=>$card->getCard(),
                            'token'=>$card->getToken(),
                            'data'=>$card->getData(),
                            'client'=>$transaction->client,
                            'driver'=>$transaction->driver
                        ]);
                        $newCard->save();
                        $transaction->card()->associate($newCard);
                    }

                    $transaction->update([
                        'status' => Transaction::STATUS_SUCCESSFUL,
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now')
                    ]);
                    break;
                case 'reversed':
                case 'refund':
                    $trans = new Transaction();
                    $trans->parent()->associate($transaction);
                    $trans->order()->associate($transaction->order);
                    $trans->fill([
                        'type' => Transaction::TYPE_PAYMENT,
                        'status' => Transaction::STATUS_REFUND,
                        'invoice' => $invoice->getInvoice(),
                        'processed_at' => new DateTime('now')
                    ]);
                    $trans->save();
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
