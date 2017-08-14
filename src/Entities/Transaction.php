<?php
declare(strict_types=1);

namespace RabbitCMS\Payments\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use function PHPSTORM_META\type;
use RabbitCMS\Payments\Contracts\OrderInterface;
use RabbitCMS\Payments\Contracts\PaymentProviderInterface;
use RabbitCMS\Payments\Contracts\TransactionInterface;
use RabbitCMS\Payments\Facade\Payments;
use RabbitCMS\Payments\Factory;

/**
 * Class Transaction
 *
 * @package RabbitCMS\Payments\Entities
 * @property-read int              $id
 * @property-read string           $driver
 * @property-read int              $status
 * @property-read int              $type
 * @property-read int              $parent_id
 * @property-read Transaction|null $parent
 * @property-read OrderInterface   $order
 *
 *
 */
class Transaction extends Model implements TransactionInterface
{
    protected $table = 'payments_transactions';

    protected $fillable = [
        'driver',
        'status',
        'amount',
        'invoice'
    ];

    protected $casts = [
        'status' => 'int',
        'type' => 'int',
        'amount' => 'float'
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
        return $this->belongsTo(Transaction::class, 'parent_id');
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

    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        // TODO: Implement getAmount() method.
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
}
