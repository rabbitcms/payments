<?php

declare(strict_types=1);

namespace RabbitCMS\Payments\Entities;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read int $id
 * @property-read string $driver
 * @property-read string $client
 * @property-read int $status
 * @property-read int $type
 * @property-read int|null $parent_id
 * @property-read Transaction|null $parent
 * @property-read OrderInterface $order
 * @property-read string $invoice
 * @property-read float $amount
 * @property-read float $commission
 * @property-read array $options
 * @property-read Carbon|null $processed_at
 * @property-read int|null $card_id
 * @property-read CardToken|null $card
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
        'commission',
    ];

    protected $casts = [
        'status' => 'int',
        'type' => 'int',
        'amount' => 'float',
        'commission' => 'float',
        'options' => 'array',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function order(): MorphTo
    {
        return $this->morphTo('order');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(CardToken::class, 'card_id');
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order()->getResults();
    }

    public function getOptions(): array
    {
        return $this->options ?? [];
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getInvoice(): string
    {
        return (string) $this->invoice;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCommission(): float
    {
        return $this->commission;
    }

    public function getTransactionId(): string
    {
        return (string) $this->id;
    }

    public function getProvider(): PaymentProviderInterface
    {
        return Payments::driver($this->driver);
    }

    public function getCardId()
    {
        return $this->card_id;
    }

    public function getCard(): ?CardTokenInterface
    {
        return $this->card;
    }

    public function getDateTime(): DateTimeInterface
    {
        return $this->processed_at;
    }
}
