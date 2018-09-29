<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use RabbitCMS\Payments\Contracts\CardTokenInterface;
use RabbitCMS\Payments\Contracts\OrderInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;
use RabbitCMS\Payments\Contracts\TransactionInterface;
use RabbitCMS\Payments\Facade\Payments;

/**
 * Class Transaction
 *
 * @package RabbitCMS\Payments\Entities
 * @property-read int              $id
 * @property-read string           $driver
 * @property-read string           $client
 * @property-read int              $status
 * @property-read int              $type
 * @property-read int              $parent_id
 * @property-read Transaction|null $parent
 * @property-read OrderInterface   $order
 * @property-read string           $invoice
 * @property-read float            $amount
 * @property-read array            $options
 * @property-read Carbon|null      $processed_at
 * @property-read int|null         $card_id
 * @property-read CardToken|null   $card
 */
class Transaction extends Model implements TransactionInterface
{
    protected $table = 'payments_transactions';

    protected $fillable = [
        'driver',
        'client',
        'status',
        'type',
        'amount',
        'invoice',
        'processed_at',
        'options',
    ];

    protected $casts = [
        'status' => 'int',
        'type' => 'int',
        'amount' => 'float',
        'options' => 'array',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING
    ];

    /**
     * @return MorphTo
     */
    public function order(): MorphTo
    {
        return $this->morphTo('order');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(CardToken::class, 'card_id');
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return OrderInterface
     */
    public function getOrder(): OrderInterface
    {
        return $this->order()->getResults();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getInvoice(): string
    {
        return (string)$this->invoice;
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
        return (string)$this->id;
    }

    /**
     * @return PaymentProviderInterface
     */
    public function getProvider(): PaymentProviderInterface
    {
        return Payments::driver($this->driver);
    }

    /**
     * @return int|null
     */
    public function getCardId()
    {
        return $this->card_id;
    }

    /**
     * @return CardTokenInterface|null
     */
    public function getCard()
    {
        return $this->card;
    }
}
